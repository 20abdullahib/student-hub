<form id="search-form" class="mb-3 position-relative">
    @csrf
    <div class="input-group">
        <input type="text" id="resource-search" class="form-control"
            placeholder="Search by code or name of subject">
        <button type="submit" class="btn btn-primary">Search</button>
    </div>
    {{-- <div id="resource-suggestions-container" class="position-absolute w-75"></div> --}}
    <div id="resource-suggestions-container" class="suggestions-container position-absolute"></div>
</form>
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <select id="department-filter" class="form-select">
            <option value="">Department</option>
            @foreach ($departments as $department)
                <option value="{{ $department->id }}">{{ $department->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <select id="branch-filter" class="form-select">
            <option value="">Branches</option>
            @foreach ($branches as $branch)
                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
            @endforeach
        </select>
    </div>
    {{-- <div class="col-md-4">
        <select id="sort-filter" class="form-select">
            <option value="">Sort</option>
            <option value="Newest">Newest</option>
            <option value="Oldest">Oldest</option>
        </select>
    </div> --}}
</div>
