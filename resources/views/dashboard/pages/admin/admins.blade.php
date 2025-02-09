@extends('dashboard.layout.layout')

@section('title', 'Admins')

@section('custom-css')
    <style>
        /* Style for category header items in the autocomplete menu */
        .ui-autocomplete-category {
            font-weight: bold;
            margin: 0.8em 0 0.2em;
            padding: 0.2em 1em;
            border-top: 1px solid #ccc;
            background-color: #f7f7f7;
        }
    </style>
@endsection

@section('content')
    <div class="py-4">
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
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Admins</li>
            </ol>
        </nav>

        <!-- Page Heading & Add Button -->
        @can('add admins')
            <div class="d-flex justify-content-between w-100 flex-wrap">
                <div class="mb-3 mb-lg-0">
                    <h1 class="h4">Admins</h1>
                    <a href="{{ route('admin.create') }}" class="btn btn-primary">
                        <svg class="icon icon-xxs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        Add New Admin
                    </a>
                </div>
            </div>
        @endcan
    </div>

    <!-- Filter Form -->
    <div class="card border-0 shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.index') }}" method="GET">
                <div class="row">
                    <!-- Filter by Branch -->
                    <div class="col-md-3">
                        <select name="branch_id" class="form-control">
                            <option value="">Filter by Branch</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}"
                                    {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Filter by Department -->
                    <div class="col-md-3">
                        <select name="department_id" class="form-control">
                            <option value="">Filter by Department</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}"
                                    {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Filter / Reset Buttons -->
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('admin.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Admins Table -->
    <div class="card border-0 shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <form id="search-form" action="{{ route('admin.index') }}" method="GET">
                    <div class="mb-3 d-flex justify-content-between">
                        <input id="search" type="text" name="search" class="form-control"
                            placeholder="Search by name or email" value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary ms-2">Search</button>
                    </div>
                </form>
                <table class="table table-centered table-nowrap mb-0 rounded text-center">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Branch</th>
                            <th>Department</th>
                            @can('edit admins')
                                <th>Role</th>
                            @endcan
                            @can('delete admins')
                                <th class="rounded-end">Actions</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($admins as $admin)
                            <tr class="hover-shadow">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $admin->name }}</td>
                                <td>{{ $admin->email }}</td>
                                <td>{{ $admin->branch->name ?? 'N/A' }}</td>
                                <td>{{ $admin->department->name ?? 'N/A' }}</td>
                                @can('edit admins')
                                    <td class="role-edit" data-admin-id="{{ $admin->id }}">
                                        <span class="role-text">{{ Str::title($admin->role) }}</span>
                                        <select class="role-select form-select d-none"
                                            style="width: 10em; display: inline-block;">
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->name }}"
                                                    {{ $admin->role == $role->name ? 'selected' : '' }}>
                                                    {{ Str::title($role->name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                @endcan
                                @can('delete admins')
                                    <td>
                                        <form action="{{ route('admin.destroy', $admin->id) }}" method="POST"
                                            style="display:inline;"
                                            onsubmit="return confirm('Are you sure you want to delete this admin?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                @endcan
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">No admins found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            <div class="d-flex justify-content-center">
                {{ $admins->appends(request()->input())->links() }}
            </div>
        </div>
    </div>

@endsection


@section('custom-scripts')
    <script>
        $(document).ready(function() {
            // --- Autocomplete Widget Setup ---
            $.widget("custom.catcomplete", $.ui.autocomplete, {
                _renderMenu: function(ul, items) {
                    var that = this,
                        currentCategory = "";
                    $.each(items, function(index, item) {
                        if (item.category !== currentCategory) {
                            ul.append("<li class='ui-autocomplete-category'>" + item.category +
                                "</li>");
                            currentCategory = item.category;
                        }
                        that._renderItemData(ul, item);
                    });
                }
            });

            // Initialize autocomplete for the search field (if used)
            $("#search").catcomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('admin.search-suggestions') }}",
                        dataType: "json",
                        data: {
                            query: request.term
                        },
                        success: function(data) {
                            response(data);
                        },
                        error: function() {
                            response([]);
                        }
                    });
                },
                minLength: 1,
                select: function(event, ui) {
                    $("#search").val(ui.item.value);
                    return false;
                }
            });
            // --- End of Autocomplete Setup ---

            // --- Inline Editing for the Role Column ---
            // When the role text is clicked, hide it and show the select dropdown.
            $('.role-edit .role-text').on('click', function() {
                var $parent = $(this).closest('.role-edit');
                $(this).hide();
                $parent.find('.role-select').removeClass('d-none').focus();
            });

            // When a new role is selected from the dropdown, update via AJAX.
            $('.role-edit .role-select').on('change', function() {
                var newRole = $(this).val();
                var $parent = $(this).closest('.role-edit');
                var adminId = $parent.data('admin-id');

                $.ajax({
                    // URL includes the /dashboard prefix to match your route
                    url: '/dashboard/admin/' + adminId + '/update-role',
                    method: 'PATCH',
                    data: {
                        role: newRole,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // Update the displayed role text
                        $parent.find('.role-text').text(response.role_label).show();
                        // Hide the select dropdown
                        $parent.find('.role-select').addClass('d-none');
                        // Display the success message
                        alert(response.message);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseJSON);
                        alert('Error updating role. Please try again.');
                        $parent.find('.role-select').addClass('d-none');
                        $parent.find('.role-text').show();
                    }
                });
            });

            // If the select loses focus without a change, hide it and show the role text again.
            $('.role-edit .role-select').on('blur', function() {
                var $parent = $(this).closest('.role-edit');
                $(this).addClass('d-none');
                $parent.find('.role-text').show();
            });
            // --- End of Inline Editing ---
        });
    </script>
    </script>
@endsection
