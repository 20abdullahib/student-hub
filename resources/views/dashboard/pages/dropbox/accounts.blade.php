@extends('dashboard.layout.layout')

@section('title', 'Dropbox Accounts')

@section('content')
    <div class="py-4">
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
            <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard.index') }}">
                        <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                    </a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dropbox Controllers</a></li>
                <li class="breadcrumb-item active" aria-current="page">Accounts</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between w-100 flex-wrap">
            <div class="mb-3 mb-lg-0">
                <h1 class="h4">Dropbox Accounts</h1>
                <a href="{{ route('dropbox.account.form') }}" class="btn btn-primary">
                    <svg class="icon icon-xxs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add New Account
                </a>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-centered table-nowrap mb-0 rounded text-center">
                    <thead class="thead-light">
                        <tr>
                            <th class="border-0 rounded-start">#</th>
                            <th class="border-0">Email</th>
                            <th class="border-0">Client Id</th>
                            <th class="border-0">Department</th>
                            <th class="border-0">Remaining Storage</th>
                            <th class="border-0 rounded-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($accounts as $account)
                            <!-- Item -->
                            <tr class="hover-shadow">
                                <td class="align-middle">
                                    <a href="#" class="text-primary fw-bold">{{ $loop->iteration }}</a>
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <svg class="icon icon-xxs text-gray-500 me-2" viewBox="0 0 48 48"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M37.75 9c2.9 0 5.25 2.35 5.25 5.25v19.5c0 2.9-2.35 5.25-5.25 5.25h-27.5A5.25 5.25 0 0 1 5 33.75v-19.5C5 11.35 7.35 9 10.25 9zm2.75 9.351-15.898 8.744a1.25 1.25 0 0 1-1.077.061l-.127-.06L7.5 18.35v15.4a2.75 2.75 0 0 0 2.75 2.75h27.5a2.75 2.75 0 0 0 2.75-2.75zM37.75 11.5h-27.5a2.75 2.75 0 0 0-2.75 2.75v1.249L24 24.573l16.5-9.075V14.25a2.75 2.75 0 0 0-2.75-2.75"
                                                fill="#212121" />
                                        </svg>
                                        <span class="fw-bold">{{ $account->email }}</span>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    {{ $account->client_id }}
                                </td>
                                <td class="align-middle">
                                    {{ $account->department_name }}
                                </td>
                                <td class="align-middle">
                                    <div class="row d-flex align-items-center justify-content-center">
                                        <div class="col-12 col-xl-2 px-0">
                                            <div class="small fw-bold">
                                                {{ number_format($account->remaining_percentage, 2) }}%</div>
                                        </div>
                                        <div class="col-12 col-xl-10 px-0 px-xl-3">
                                            <div class="progress progress-lg mb-0">
                                                <div class="progress-bar bg-gradient-dark" role="progressbar"
                                                    aria-valuenow="{{ number_format($account->remaining_percentage, 2) }}"
                                                    aria-valuemin="0" aria-valuemax="100"
                                                    style="width: {{ number_format($account->remaining_percentage, 2) }}%;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <form action="{{ route('dropbox.account.delete', $account->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm" type="submit">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            <!-- End of Item -->
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection


