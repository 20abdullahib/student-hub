@extends('dashboard.layout.layout')

@section('title', 'Manage Roles & Permissions')

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
            <li class="breadcrumb-item"><a href="#">Roles & Permissions</a></li>
        </ol>
    </nav>

    <!-- Page Heading -->
    <div class="py-4">
        <div class="d-flex justify-content-between w-100 flex-wrap">
            <div class="mb-3 mb-lg-0">
                <h1 class="h4">Manage Roles & Permissions</h1>
                <p class="mb-0">Use the forms below to add a new role or a new permission.</p>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Add Role Section -->
        <div class="col-12 col-md-6 mb-4">
            <div class="card border-0 shadow components-section">
                <div class="card-header">
                    <h5 class="mb-0">Add New Role</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('permission.store.role') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="role_name" class="form-label">Role Name</label>
                            <input type="text" name="role_name" id="role_name" class="form-control"
                                placeholder="Enter role name" value="{{ old('role_name') }}" required>
                        </div>
                        @if ($permissions->count())
                            <div class="mb-4">
                                <label class="form-label">Assign Existing Permissions</label>
                                <div class="row">
                                    @foreach ($permissions as $permission)
                                        <div class="col-md-4 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="role_permissions[]"
                                                    value="{{ $permission->name }}" id="role_perm_{{ $permission->id }}"
                                                    {{ in_array($permission->name, old('role_permissions', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="role_perm_{{ $permission->id }}">
                                                    {{ ucfirst($permission->name) }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Add Role</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Add Permission Section -->
        <div class="col-12 col-md-6 mb-4">
            <div class="card border-0 shadow components-section">
                <div class="card-header">
                    <h5 class="mb-0">Add New Permission</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('permission.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="permission_name" class="form-label">Permission Name</label>
                            <input type="text" name="permission_name" id="permission_name" class="form-control"
                                placeholder="Enter permission name" value="{{ old('permission_name') }}" required>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Add Permission</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
