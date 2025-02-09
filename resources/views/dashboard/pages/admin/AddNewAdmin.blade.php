@extends('dashboard.layout.layout')

@section('title', 'Add New Admin')

@section('content')
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
        <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard.index') }}">
                    <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                </a>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Admins</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add New Admin</li>
        </ol>
    </nav>

    <!-- Page Heading -->
    <div class="py-4">
        <div class="d-flex justify-content-between w-100 flex-wrap">
            <div class="mb-3 mb-lg-0">
                <h1 class="h4">Add New Admin</h1>
                <p class="mb-0">Fill in the details to create a new admin account.</p>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card border-0 shadow components-section">
                <div class="card-body">
                    <form action="{{ route('admin.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-lg-6 col-md-12">
                                <!-- Name -->
                                <div class="mb-4">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Enter full name" value="{{ old('name') }}" required>
                                </div>
                                <!-- Email -->
                                <div class="mb-4">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" name="email" id="email" class="form-control"
                                        placeholder="Enter email address" value="{{ old('email') }}" required>
                                </div>
                                <!-- Password -->
                                <div class="mb-4">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" name="password" id="password" class="form-control"
                                        placeholder="Enter password" required>
                                </div>
                                <!-- Confirm Password -->
                                <div class="mb-4">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="form-control" placeholder="Confirm password" required>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-lg-6 col-md-12">
                                <!-- Department -->
                                <div class="mb-4">
                                    <label for="department_id" class="form-label">Department</label>
                                    <select name="department_id" id="department_id" class="form-select" required>
                                        <option value="">Select Department</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}"
                                                {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Branch -->
                                <div class="mb-4">
                                    <label for="branch_id" class="form-label">Branch</label>
                                    <select name="branch_id" id="branch_id" class="form-select" required>
                                        <option value="">Select Branch</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}"
                                                {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Role -->
                                <div class="mb-4">
                                    <label for="role" class="form-label">Role</label>
                                    <select name="role" id="role" class="form-select" required>
                                        <option value="">Select Role</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}"
                                                {{ old('role') == $role->name ? 'selected' : '' }}>
                                                {{ Str::title($role->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Add Admin</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
