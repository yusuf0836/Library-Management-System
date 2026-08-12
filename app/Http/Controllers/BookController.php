<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $books = Book::with(['category', 'publisher', 'authors'])
            ->when($search, function ($query, $search) {
                $query->where(function ($bookQuery) use ($search) {
                    $bookQuery->where('title', 'like', '%' . $search . '%')
                        ->orWhere('isbn', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('books.index', compact('books', 'search'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $publishers = Publisher::orderBy('name')->get();
        $authors = Author::orderBy('name')->get();

        return view('books.create', compact('categories', 'publishers', 'authors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'isbn' => ['nullable', 'string', 'max:30', 'unique:books,isbn'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'publisher_id' => ['nullable', 'exists:publishers,id'],
            'edition' => ['nullable', 'string', 'max:100'],
            'publication_year' => ['nullable', 'integer', 'min:1000', 'max:' . date('Y')],
            'description' => ['nullable', 'string', 'max:3000'],
            'authors' => ['required', 'array', 'min:1'],
            'authors.*' => ['exists:authors,id'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $coverImage = null;

            if ($request->hasFile('cover_image')) {
                $coverImage = $request->file('cover_image')
                    ->store('book-covers', 'public');
            }

        $book = Book::create([
            'title' => $validated['title'],
            'isbn' => $validated['isbn'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'publisher_id' => $validated['publisher_id'] ?? null,
            'edition' => $validated['edition'] ?? null,
            'publication_year' => $validated['publication_year'] ?? null,
            'description' => $validated['description'] ?? null,
            'cover_image' => $coverImage,
        ]);

        $book->authors()->sync($validated['authors']);

        return redirect()
            ->route('books.index')
            ->with('success', 'Book added successfully.');
    }

    public function edit(Book $book)
    {
        $book->load('authors');

        $categories = Category::orderBy('name')->get();
        $publishers = Publisher::orderBy('name')->get();
        $authors = Author::orderBy('name')->get();

        return view('books.edit', compact('book', 'categories', 'publishers', 'authors'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'isbn' => [
                'nullable',
                'string',
                'max:30',
                Rule::unique('books', 'isbn')->ignore($book->id),
            ],
            'category_id' => ['nullable', 'exists:categories,id'],
            'publisher_id' => ['nullable', 'exists:publishers,id'],
            'edition' => ['nullable', 'string', 'max:100'],
            'publication_year' => ['nullable', 'integer', 'min:1000', 'max:' . date('Y')],
            'description' => ['nullable', 'string', 'max:3000'],
            'authors' => ['required', 'array', 'min:1'],
            'authors.*' => ['exists:authors,id'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $coverImage = $book->cover_image;

            if ($request->hasFile('cover_image')) {
                if ($book->cover_image) {
                    Storage::disk('public')->delete($book->cover_image);
                }

                $coverImage = $request->file('cover_image')
                    ->store('book-covers', 'public');
            }

        $book->update([
            'title' => $validated['title'],
            'isbn' => $validated['isbn'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'publisher_id' => $validated['publisher_id'] ?? null,
            'edition' => $validated['edition'] ?? null,
            'publication_year' => $validated['publication_year'] ?? null,
            'description' => $validated['description'] ?? null,
            'cover_image' => $coverImage,
        ]);

        $book->authors()->sync($validated['authors']);

        return redirect()
            ->route('books.index')
            ->with('success', 'Book updated successfully.');
    }

    public function show(Book $book)
    {
        $book->load([
            'category',
            'publisher',
            'authors',
            'copies',
        ]);

        $totalCopies = $book->copies->count();

        $availableCopies = $book->copies
            ->where('status', 'available')
            ->count();

        $issuedCopies = $book->copies
            ->where('status', 'issued')
            ->count();

        return view(
            'books.show',
            compact(
                'book',
                'totalCopies',
                'availableCopies',
                'issuedCopies'
            )
        );
    }

    public function destroy(Book $book)
    {
        if ($book->copies()->exists()) {
            return redirect()
                ->route('books.index')
                ->with('error', 'This book cannot be deleted because it has one or more physical copies.');
        }

        $book->authors()->detach();

        $book->delete();

        return redirect()
            ->route('books.index')
            ->with('success', 'Book deleted successfully.');
    }
}