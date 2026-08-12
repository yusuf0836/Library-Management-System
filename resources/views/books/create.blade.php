<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book | Library Management System</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark" style="background:#1e3a8a;">
        <div class="container">
            <span class="navbar-brand mb-0 h1">Library Management System</span>
        </div>
    </nav>

    <main class="container py-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h2 class="mb-1">Add New Book</h2>
                <p class="text-muted mb-4">Enter the book information below.</p>

                <form
                    action="{{ route('books.store') }}"
                    method="POST"
                    enctype="multipart/form-data"
                >
                    @csrf

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
                                value="{{ old('title') }}"
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
                                value="{{ old('isbn') }}"
                                placeholder="Example: 978-984-000-000-0"
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
                                        @selected(old('category_id') == $category->id)
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
                                        @selected(old('publisher_id') == $publisher->id)
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
                                @foreach ($authors as $author)
                                    <option
                                        value="{{ $author->id }}"
                                        @selected(in_array($author->id, old('authors', [])))
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
                                value="{{ old('edition') }}"
                                placeholder="Example: 2nd Edition"
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
                                value="{{ old('publication_year') }}"
                                min="1000"
                                max="{{ date('Y') }}"
                            >

                            @error('publication_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="cover_image" class="form-label">
                                Book Cover Image
                            </label>

                            <input
                                id="cover_image"
                                type="file"
                                name="cover_image"
                                class="form-control @error('cover_image') is-invalid @enderror"
                                accept=".jpg,.jpeg,.png,.webp"
                            >

                            <small class="text-muted">
                                JPG, JPEG, PNG, or WEBP. Maximum size: 2 MB.
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
                            >{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button class="btn btn-primary" type="submit">
                            Save Book
                        </button>

                        <a class="btn btn-secondary" href="{{ route('books.index') }}">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>