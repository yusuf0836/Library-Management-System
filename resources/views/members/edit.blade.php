@extends('layouts.app')

@section('title', 'Edit Member | Library Management System')
@section('page-title', 'Edit Library Member')
@section('page-subtitle', 'Update member profile, account, and membership status')

@section('content')
    <div class="row">
        <div class="col-xl-9">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form
                        action="{{ route('members.update', $member) }}"
                        method="POST"
                    >
                        @csrf
                        @method('PUT')

                        @include('members.form', ['member' => $member])

                        <div class="mt-4">
                            <button class="btn btn-primary" type="submit">
                                Update Member
                            </button>

                            <a
                                class="btn btn-outline-secondary"
                                href="{{ route('members.index') }}"
                            >
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection