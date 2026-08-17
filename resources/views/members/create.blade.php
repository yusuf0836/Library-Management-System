@extends('layouts.app')

@section('title', 'Add Member | Library Management System')
@section('page-title', 'Add Library Member')
@section('page-subtitle', 'Create a member profile and login account')

@section('content')
    <div class="row">
        <div class="col-xl-9">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="{{ route('members.store') }}" method="POST">
                        @csrf

                        @include('members.form', ['member' => null])

                        <div class="mt-4">
                            <button class="btn btn-primary" type="submit">
                                Save Member
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