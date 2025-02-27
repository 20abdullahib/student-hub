<form method="POST" action="{{ route('team-members.store') }}">
    @csrf
    <div class="row">
        <!-- Name -->
        <div class="col-md-6 mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" required>
        </div>
        <!-- Branch ID as Select -->
        <div class="col-md-6 mb-3">
            <label for="branch_id" class="form-label">Branch</label>
            <select class="form-select" id="branch_id" name="branch_id" required>
                <option value="">Select Branch</option>
                @foreach ($branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <!-- Year Joined as Select -->
        <div class="col-md-6 mb-3">
            <label for="year_joined" class="form-label">Year Joined</label>
            <select class="form-select" id="year_joined" name="year_joined" required>
                <option value="">Select Year Joined</option>
                @for ($year = 2018; $year <= date('Y'); $year++)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endfor
            </select>
        </div>
        <!-- Image URL -->
        <div class="col-md-6 mb-3">
            <label for="image" class="form-label">Image URL</label>
            <input type="url" class="form-control" id="image" name="image" placeholder="Enter image URL" required>
        </div>
    </div>
    <div class="row">
        <!-- Patch -->
        <div class="col-md-6 mb-3">
            <label for="patch" class="form-label">Patch</label>
            <select class="form-select" id="patch" name="patch" required>
                <option value="">Select Patch</option>
                @for ($patch = 60; $patch <= 75; $patch++)
                    <option value="{{ $patch }}">{{ $patch }}</option>
                @endfor
            </select>
        </div>
        <!-- Role -->
        <div class="col-md-6 mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" id="role" name="role" required>
                <option value="">Select Role</option>
                <option value="OC">OC</option>
                <option value="BR">BR</option>
                <option value="HR">HR</option>
                <option value="IT">IT</option>
            </select>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Save Team Member</button>
</form>