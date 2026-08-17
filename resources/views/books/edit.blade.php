@extends('layouts.app')

@section('title', 'Edit Book | Library Management System')
@section('page-title', 'Edit Book')
@section('page-subtitle', 'Update selected book information')

@section('content')
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">

                <form
                    action="{{ route('books.update', $book) }}"
                    method="POST"
                    enctype="multipart/form-data"
                >
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label for="title" class="form-label">
                                Book Title <span class="text-danger">*</span>
                            </label>

                            <input
                                id="title"
                                type="text"
                                name="title"
                                class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title', $book->title) }}"
                                required
                            >

                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="isbn" class="form-label">ISBN</label>

                            <input
                                id="isbn"
                                type="text"
                                name="isbn"
                                class="form-control @error('isbn') is-invalid @enderror"
                                value="{{ old('isbn', $book->isbn) }}"
                            >

                            @error('isbn')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="category_id" class="form-label">Category</label>

                            <select id="category_id" name="category_id" class="form-select">
                                <option value="">Select a category</option>

                                @foreach ($categories as $category)
                                    <option
                                        value="{{ $category->id }}"
                                        @selected(old('category_id', $book->category_id) == $category->id)
                                    >
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="publisher_id" class="form-label">Publisher</label>

                            <select id="publisher_id" name="publisher_id" class="form-select">
                                <option value="">Select a publisher</option>

                                @foreach ($publishers as $publisher)
                                    <option
                                        value="{{ $publisher->id }}"
                                        @selected(old('publisher_id', $book->publisher_id) == $publisher->id)
                                    >
                                        {{ $publisher->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="authors" class="form-label">
                                Author(s) <span class="text-danger">*</span>
                            </label>

                            <select
                                id="authors"
                                name="authors[]"
                                class="form-select @error('authors') is-invalid @enderror"
                                multiple
                                size="5"
                                required
                            >
                                @php
                                    $selectedAuthors = old(
                                        'authors',
                                        $book->authors->pluck('id')->toArray()
                                    );
                                @endphp

                                @foreach ($authors as $author)
                                    <option
                                        value="{{ $author->id }}"
                                        @selected(in_array($author->id, $selectedAuthors))
                                    >
                                        {{ $author->name }}
                                    </option>
                                @endforeach
                            </select>

                            <small class="text-muted">
                                Hold Ctrl key to select multiple authors.
                            </small>

                            @error('authors')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="edition" class="form-label">Edition</label>

                            <input
                                id="edition"
                                type="text"
                                name="edition"
                                class="form-control"
                                value="{{ old('edition', $book->edition) }}"
                            >
                        </div>

                        <div class="col-md-3">
                            <label for="publication_year" class="form-label">
                                Publication Year
                            </label>

                            <input
                                id="publication_year"
                                type="number"
                                name="publication_year"
                                class="form-control @error('publication_year') is-invalid @enderror"
                                value="{{ old('publication_year', $book->publication_year) }}"
                                min="1000"
                                max="{{ date('Y') }}"
                            >

                            @error('publication_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="cover_image" class="form-label">
                                Update Book Cover
                            </label>

                            @if ($book->cover_image)
                                <div class="mb-2">
                                    <img
                                        src="{{ asset('storage/' . $book->cover_image) }}"
                                        alt="{{ $book->title }}"
                                        style="width: 90px; height: 120px; object-fit: cover;"
                                        class="border rounded"
                                    >
                                </div>
                            @endif

                            <input
                                id="cover_image"
                                type="file"
                                name="cover_image"
                                class="form-control @error('cover_image') is-invalid @enderror"
                                accept=".jpg,.jpeg,.png,.webp"
                            >

                            <small class="text-muted">
                                Leave empty to keep the current cover image.
                            </small>

                            @error('cover_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label">Description</label>

                            <textarea
                                id="description"
                                name="description"
                                class="form-control"
                                rows="4"
                            >{{ old('description', $book->description) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button class="btn btn-primary" type="submit">
                            Update Book
                        </button>

                        <a class="btn btn-secondary" href="{{ route('books.index') }}">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
@endsection