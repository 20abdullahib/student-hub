@extends('dashboard.layout.layout')

@section('content')
<div class="container-fluid px-0 px-lg-2">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
        <h1 class="h3 mb-0 text-gray-800">Settings</h1>
    </div>

    <!-- Team Members Section -->
    <div class="card shadow mb-4">
        <!-- Outer Collapse Header -->
        <div class="card-header">
            <h5 class="mb-0">
                <button class="btn btn-link text-decoration-none" type="button" data-bs-toggle="collapse"
                    data-bs-target="#teamMembers" aria-expanded="false" aria-controls="teamMembers">
                    <i class="bi bi-chevron-down"></i> Team Members Details
                </button>
            </h5>
        </div>
        <!-- Outer Collapse Content -->
        <div id="teamMembers" class="collapse">
            <div class="card-body">
                <!-- Nested Collapse: Add New Member Form -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <button class="btn btn-link text-decoration-none" type="button" data-bs-toggle="collapse"
                                data-bs-target="#addNewMember" aria-expanded="false" aria-controls="addNewMember">
                                <i class="bi bi-chevron-down"></i> Add New Member
                            </button>
                        </h6>
                    </div>
                    <div id="addNewMember" class="collapse">
                        <div class="card-body">
                            @include('dashboard.pages.settings.includes.AddNewMember')
                        </div>
                    </div>
                </div>
                <!-- End Nested Collapse: Add New Member -->

                <!-- Edit Team Members Section -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <button class="btn btn-link text-decoration-none" type="button" data-bs-toggle="collapse"
                                data-bs-target="#EditTeamMember" aria-expanded="false" aria-controls="EditTeamMember">
                                <i class="bi bi-chevron-down"></i> Edit Team Members
                            </button>
                        </h6>
                    </div>
                    <div id="EditTeamMember" class="collapse">
                        <div class="card-body px-1 px-lg-3">
                            <!-- Group Team Members by Generation -->
                            @php
                                $groupedMembers = $teamMembers->groupBy('year_joined');
                            @endphp

                            @foreach ($groupedMembers as $year => $members)
                                <div class="card mb-2 overflow-auto" style="max-width: 100%; overflow-x: scroll;">
                                    <div class="card-header p-1">
                                        <h6 class="mb-0">
                                            <button class="btn btn-link text-decoration-none" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#generation{{ $year }}" aria-expanded="false"
                                                aria-controls="generation{{ $year }}">
                                                <i class="bi bi-chevron-down"></i> Generation: {{ $year }}
                                            </button>
                                        </h6>
                                    </div>
                                    <div id="generation{{ $year }}" class="collapse">
                                        <div class="card-body px-1 px-lg-3">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Branch</th>
                                                        <th>Year Joined</th>
                                                        <th>Patch</th>
                                                        <th>Image</th>
                                                        <th>Publish</th>
                                                        <th>Role</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($members as $member)
                                                        <tr data-id="{{ $member->id }}">
                                                            <td class="editable" data-field="name">{{ $member->name }}</td>
                                                            <td class="editable" data-field="branch_id">{{ $member->branch_id }}</td>
                                                            <td class="editable" data-field="year_joined">{{ $member->year_joined }}</td>
                                                            <td class="editable" data-field="patch">{{ $member->patch }}</td>
                                                            <td class="editable" data-field="image">
                                                                <a href="{{ $member->image }}" target="_blank">View Image</a>
                                                            </td>
                                                            <td data-field="publish">
                                                                <input type="checkbox" class="publish-checkbox" data-id="{{ $member->id }}" {{ $member->publish == 1 ? 'checked' : '' }}>
                                                            </td>
                                                            <td class="editable" data-field="role">{{ $member->role }}</td>
                                                            <td>
                                                                <form action="{{route('team-members.destroy',$member->id)}}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div><!-- End card-body for Edit Team Members -->
                    </div>
                </div>
                <!-- End Edit Team Members Section -->
            </div><!-- End Outer card-body -->
        </div><!-- End Outer Collapse -->
    </div><!-- End Card -->
</div>
@endsection

@section('custom-scripts')
<script>
    var TEAM_MEMBERS_STORE_URL = '{{ route("team-members.store") }}';
    var TEAM_MEMBER_UPDATE_URL = '{{ url("settings/team-members") }}';
    var TEAM_BRANCHES = @json($branches);
    var CSRF_TOKEN = '{{ csrf_token() }}';
</script>
<script src="{{ asset('assets/Dashboard/scripts/settings.js') }}"></script>
@endsection
