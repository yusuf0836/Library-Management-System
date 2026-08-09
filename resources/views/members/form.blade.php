<div class="row g-3">
    <div class="col-md-6">
        <label for="name" class="form-label">
            Full Name <span class="text-danger">*</span>
        </label>

        <input
            id="name"
            type="text"
            name="name"
            class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $member->user->name ?? '') }}"
            required
        >

        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="member_code" class="form-label">
            Member Code <span class="text-danger">*</span>
        </label>

        <input
            id="member_code"
            type="text"
            name="member_code"
            class="form-control @error('member_code') is-invalid @enderror"
            value="{{ old('member_code', $member->member_code ?? '') }}"
            placeholder="Example: MEM-2026-001"
            required
        >

        @error('member_code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="email" class="form-label">
            Email Address <span class="text-danger">*</span>
        </label>

        <input
            id="email"
            type="email"
            name="email"
            class="form-control @error('email') is-invalid @enderror"
            value="{{ old('email', $member->user->email ?? '') }}"
            required
        >

        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="phone" class="form-label">Phone Number</label>

        <input
            id="phone"
            type="text"
            name="phone"
            class="form-control"
            value="{{ old('phone', $member->phone ?? '') }}"
        >
    </div>

    <div class="col-md-6">
        <label for="department" class="form-label">Department</label>

        <input
            id="department"
            type="text"
            name="department"
            class="form-control"
            value="{{ old('department', $member->department ?? '') }}"
            placeholder="Example: Computer Science"
        >
    </div>

    <div class="col-md-6">
        <label for="joined_at" class="form-label">
            Joining Date <span class="text-danger">*</span>
        </label>

        <input
            id="joined_at"
            type="date"
            name="joined_at"
            class="form-control @error('joined_at') is-invalid @enderror"
            value="{{ old('joined_at', isset($member) ? $member->joined_at->format('Y-m-d') : date('Y-m-d')) }}"
            required
        >

        @error('joined_at')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="password" class="form-label">
            Password
            @if (! isset($member))
                <span class="text-danger">*</span>
            @else
                <small class="text-muted">(Leave blank to keep unchanged)</small>
            @endif
        </label>

        <input
            id="password"
            type="password"
            name="password"
            class="form-control @error('password') is-invalid @enderror"
            @if (! isset($member)) required @endif
        >

        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="password_confirmation" class="form-label">
            Confirm Password
        </label>

        <input
            id="password_confirmation"
            type="password"
            name="password_confirmation"
            class="form-control"
            @if (! isset($member)) required @endif
        >
    </div>

    <div class="col-md-6">
        <label for="is_active" class="form-label">
            Membership Status <span class="text-danger">*</span>
        </label>

        <select id="is_active" name="is_active" class="form-select" required>
            <option
                value="1"
                @selected(old('is_active', $member->is_active ?? true) == 1)
            >
                Active
            </option>

            <option
                value="0"
                @selected(old('is_active', $member->is_active ?? true) == 0)
            >
                Inactive
            </option>
        </select>
    </div>

    <div class="col-12">
        <label for="address" class="form-label">Address</label>

        <textarea
            id="address"
            name="address"
            class="form-control"
            rows="3"
        >{{ old('address', $member->address ?? '') }}</textarea>
    </div>
</div>