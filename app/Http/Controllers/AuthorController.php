<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AuthorController extends Controller
{
    /**
     * Display all authors.
     */
    public function index()
    {
        $authors = Author::latest()->paginate(10);

        return view('authors.index', compact('authors'));
    }

    /**
     * Show create author form.
     */
    public function create()
    {
        return view('authors.create');
    }

    /**
     * Save a new author.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150', 'unique:authors,name'],
            'biography' => ['nullable', 'string', 'max:2000'],
        ]);

        Author::create($validated);

        return redirect()
            ->route('authors.index')
            ->with('success', 'Author added successfully.');
    }

    /**
     * Show edit author form.
     */
    public function edit(Author $author)
    {
        return view('authors.edit', compact('author'));
    }

    /**
     * Update author information.
     */
    public function update(Request $request, Author $author)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('authors', 'name')->ignore($author->id),
            ],
            'biography' => ['nullable', 'string', 'max:2000'],
        ]);

        $author->update($validated);

        return redirect()
            ->route('authors.index')
            ->with('success', 'Author updated successfully.');
    }

    /**
     * Delete an author.
     */
    public function destroy(Author $author)
    {
        if ($author->books()->exists()) {
            return redirect()
                ->route('authors.index')
                ->with('error', 'This author cannot be deleted because it is linked to one or more books.');
        }

        $author->delete();

        return redirect()
            ->route('authors.index')
            ->with('success', 'Author deleted successfully.');
    }
}