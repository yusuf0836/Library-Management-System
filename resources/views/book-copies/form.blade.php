<div class="row g-3">
    <div class="col-md-6">
        <label for="book_id" class="form-label">
            Book <span class="text-danger">*</span>
        </label>

        <select
            id="book_id"
            name="book_id"
            class="form-select @error('book_id') is-invalid @enderror"
            required
        >
            <option value="">Select a book</option>

            @foreach ($books as $book)
                <option
                    value="{{ $book->id }}"
                    @selected(old('book_id', $bookCopy->book_id ?? '') == $book->id)
                >
                    {{ $book->title }}
                    @if ($book->isbn)
                        — {{ $book->isbn }}
                    @endif
                </option>
            @endforeach
        </select>

        @error('book_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="accession_number" class="form-label">
            Accession Number <span class="text-danger">*</span>
        </label>

        <input
            id="accession_number"
            type="text"
            name="accession_number"
            class="form-control @error('accession_number') is-invalid @enderror"
            value="{{ old('accession_number', $bookCopy->accession_number ?? '') }}"
            placeholder="Example: LIB-2026-001"
            required
        >

        @error('accession_number')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="shelf_location" class="form-label">Shelf Location</label>

        <input
            id="shelf_location"
            type="text"
            name="shelf_location"
            class="form-control @error('shelf_location') is-invalid @enderror"
            value="{{ old('shelf_location', $bookCopy->shelf_location ?? '') }}"
            placeholder="Example: Shelf A-02"
        >

        @error('shelf_location')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="status" class="form-label">
            Status <span class="text-danger">*</span>
        </label>

        <select id="status" name="status" class="form-select" required>
            @php
                $selectedStatus = old('status', $bookCopy->status ?? 'available');
            @endphp

            <option value="available" @selected($selectedStatus === 'available')>
                Available
            </option>

            <option value="issued" @selected($selectedStatus === 'issued')>
                Issued
            </option>

            <option value="reserved" @selected($selectedStatus === 'reserved')>
                Reserved
            </option>

            <option value="lost" @selected($selectedStatus === 'lost')>
                Lost
            </option>

            <option value="damaged" @selected($selectedStatus === 'damaged')>
                Damaged
            </option>
        </select>
    </div>
</div>