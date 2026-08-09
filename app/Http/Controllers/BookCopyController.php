<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCopy;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookCopyController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $copies = BookCopy::with('book')
            ->when($search, function ($query, $search) {
                $query->where(function ($copyQuery) use ($search) {
                    $copyQuery->where('accession_number', 'like', '%' . $search . '%')
                        ->orWhere('shelf_location', 'like', '%' . $search . '%')
                        ->orWhereHas('book', function ($bookQuery) use ($search) {
                            $bookQuery->where('title', 'like', '%' . $search . '%');
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('book-copies.index', compact('copies', 'search'));
    }

    public function create()
    {
        $books = Book::orderBy('title')->get();

        return view('book-copies.create', compact('books'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => ['required', 'exists:books,id'],
            'accession_number' => [
                'required',
                'string',
                'max:100',
                'unique:book_copies,accession_number',
            ],
            'shelf_location' => ['nullable', 'string', 'max:100'],
            'status' => ['required', Rule::in([
                'available',
                'issued',
                'reserved',
                'lost',
                'damaged',
            ])],
        ]);

        BookCopy::create($validated);

        return redirect()
            ->route('book-copies.index')
            ->with('success', 'Book copy added successfully.');
    }

    public function edit(BookCopy $bookCopy)
    {
        $books = Book::orderBy('title')->get();

        return view('book-copies.edit', compact('bookCopy', 'books'));
    }

    public function update(Request $request, BookCopy $bookCopy)
    {
        $validated = $request->validate([
            'book_id' => ['required', 'exists:books,id'],
            'accession_number' => [
                'required',
                'string',
                'max:100',
                Rule::unique('book_copies', 'accession_number')
                    ->ignore($bookCopy->id),
            ],
            'shelf_location' => ['nullable', 'string', 'max:100'],
            'status' => ['required', Rule::in([
                'available',
                'issued',
                'reserved',
                'lost',
                'damaged',
            ])],
        ]);

        $bookCopy->update($validated);

        return redirect()
            ->route('book-copies.index')
            ->with('success', 'Book copy updated successfully.');
    }

    public function destroy(BookCopy $bookCopy)
    {
        if ($bookCopy->status === 'issued') {
            return redirect()
                ->route('book-copies.index')
                ->with('error', 'An issued book copy cannot be deleted.');
        }

        $bookCopy->delete();

        return redirect()
            ->route('book-copies.index')
            ->with('success', 'Book copy deleted successfully.');
    }
}