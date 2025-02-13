<form id="search-form" class="mb-3 position-relative">
    @csrf
    <div class="input-group">
        <!-- Input Field -->
        <input type="text" id="resource-search" class="form-control ps-3 pe-4"
            placeholder="Search by code or name of subject">
        <!-- Clear Icon Inside Input -->
        <span class="position-absolute top-50 translate-middle-y end-0 me-3 d-none" style="z-index: 10; cursor: pointer;"
            id="clear-resource-search">
            <i class="fa fa-times text-muted"></i>
        </span>
        <!-- Search Button -->
        <button type="submit" class="btn btn-primary">Search</button>
    </div>
    <!-- Suggestions Container -->
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
