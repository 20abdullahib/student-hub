<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Admin;
use App\Models\Branch;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Admin::query();

        // Search filter
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhereHas('department', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('branch', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        // Filter by branch
        if ($branchId = $request->input('branch_id')) {
            $query->where('branch_id', $branchId);
        }

        // Filter by department
        if ($departmentId = $request->input('department_id')) {
            $query->where('department_id', $departmentId);
        }

        $admins = $query->paginate(10);

        // Make sure to pass $branches and $departments to the view
        $branches = Branch::all();
        $departments = Department::all();
        // $roleNames = Role::all()->pluck('name');
        $roles = Role::all();
        return view('dashboard.pages.admin.admins', compact('admins', 'branches', 'departments', 'roles'));
    }
    public function searchSuggestions(Request $request)
    {
        $query = $request->input('query');

        // Admin suggestions (names or emails)
        $adminSuggestions = Admin::where('name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->take(5)
            ->get()
            ->map(function ($admin) {
                return [
                    'value'    => $admin->name,
                    'label'    => $admin->name,
                    'category' => 'Admins'  // Category header for admin names
                ];
            });

        // Department suggestions
        $departmentSuggestions = Department::where('name', 'LIKE', "%{$query}%")
            ->take(5)
            ->get()
            ->map(function ($department) {
                return [
                    'value'    => $department->name,
                    'label'    => $department->name,
                    'category' => 'Departments'  // Category header for departments
                ];
            });

        // Branch suggestions
        $branchSuggestions = Branch::where('name', 'LIKE', "%{$query}%")
            ->take(5)
            ->get()
            ->map(function ($branch) {
                return [
                    'value'    => $branch->name,
                    'label'    => $branch->name,
                    'category' => 'Branches'  // Category header for branches
                ];
            });

        // Merge suggestions in the desired order: admins, then departments, then branches.
        $suggestions = $adminSuggestions
            ->merge($departmentSuggestions)
            ->merge($branchSuggestions);

        return response()->json($suggestions);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::all();
        $branches = Branch::all();
        $roles = Role::all();
        return view('dashboard.pages.admin.AddNewAdmin', compact('departments', 'branches', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request.
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:admins',
            'password'      => 'required|string|min:8|confirmed',
            'department_id' => 'required|exists:departments,id',
            'branch_id'     => 'required|exists:branches,id',
            'role'          => 'required|exists:roles,name',
        ]);

        // Hash the password.
        $validated['password'] = Hash::make($validated['password']);

        // Create the admin record.
        $admin = Admin::create([
            'name'          => $validated['name'],
            'email'         => $validated['email'],
            'password'      => $validated['password'],
            'department_id' => $validated['department_id'],
            'branch_id'     => $validated['branch_id'],
            'role'          => $validated['role'],
        ]);

        // Assign the role using Spatie's permission package.
        $admin->assignRole($validated['role']);

        return redirect()->route('admin.index')->with('success', 'Admin created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }
    /**
     * Update the specified admin's role.
     */
    public function updateRole(Request $request, Admin $admin)
    {
        $allowedRoles = Role::pluck('name')->toArray();
    
        $request->validate([
            'role' => 'required|in:' . implode(',', $allowedRoles),
        ]);
    
        $admin->syncRoles($request->role);
    
        $newRole = $admin->getRoleNames()->first();
        
        return response()->json([
            'role_label' => Str::title($newRole),
            'message'    => 'Role updated successfully.'
        ]);
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $admin = Admin::findOrFail($id);

        $admin->removeRole($admin->role);

        $admin->delete();

        return redirect()->route('admin.index')->with('success', 'Admin deleted successfully.');
    }
}
