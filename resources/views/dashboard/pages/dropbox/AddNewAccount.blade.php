@extends('dashboard.layout.layout')

@section('content')
     <!-- Dropbox Account Setup Form -->
     <div class="py-4">
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
            <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                <li class="breadcrumb-item">
                    <a href="{{route('dashboard.index')}}">
                        <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </a>
                </li>
                <li class="breadcrumb-item"><a href="">Dropbox Controllers</a></li>
                <li class="breadcrumb-item active" aria-current="page">New Account</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between w-100 flex-wrap">
            <div class="mb-3 mb-lg-0">
                <h1 class="h4">Dropbox Account Setup</h1>
                <p class="mb-0">Configure your Dropbox account integration.</p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card border-0 shadow components-section">
                <div class="card-body">
                    <form action="{{ route('dropbox.account.setup') }}" method="POST">
                        @csrf
                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Enter Dropbox account email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Client ID -->
                        <div class="mb-4">
                            <label for="client_id">Client ID</label>
                            <input type="text" class="form-control @error('client_id') is-invalid @enderror" id="client_id" name="client_id" placeholder="Enter Dropbox Client ID" value="{{ old('client_id') }}" required>
                            @error('client_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Client Secret -->
                        <div class="mb-4">
                            <label for="client_secret">Client Secret</label>
                            <input type="text" class="form-control @error('client_secret') is-invalid @enderror" id="client_secret" name="client_secret" placeholder="Enter Dropbox Client Secret" value="{{ old('client_secret') }}" required>
                            @error('client_secret')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Refresh Token -->
                        <div class="mb-4">
                            <label for="refresh_token">Refresh Token</label>
                            <input type="text" class="form-control @error('refresh_token') is-invalid @enderror" id="refresh_token" name="refresh_token" placeholder="Enter Dropbox Refresh Token" value="{{ old('refresh_token') }}" required>
                            @error('refresh_token')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Department -->
                        <div class="mb-4">
                            <label for="department_id">Assign to Department</label>
                            <select class="form-control @error('department_id') is-invalid @enderror" id="department_id" name="department_id" required>
                                <option value="" disabled selected>Select a department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary">Save and Refresh Access Token</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
