<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('dashboard.pages.admin.AddNewPermission', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeRole(Request $request)
    {
        // Validate the request.
        // Note: We no longer validate uniqueness for role_name since we want to update the role if it exists.
        $validated = $request->validate([
            'role_name'         => 'required|string|max:255',
            'role_permissions'  => 'nullable|array',
            'role_permissions.*'=> 'exists:permissions,name',
        ]);
    
        // Check if a role with the given name already exists.
        $role = Role::where('name', $validated['role_name'])->first();
    
        if ($role) {
            // Role already exists.
            // If permissions are provided, add them to the role without removing already assigned permissions.
            if (!empty($validated['role_permissions'])) {
                $role->givePermissionTo($validated['role_permissions']);
            }
            $message = 'Role updated successfully.';
        } else {
            // Create a new role.
            $role = Role::create(['name' => $validated['role_name']]);
            if (!empty($validated['role_permissions'])) {
                $role->syncPermissions($validated['role_permissions']);
            }
            $message = 'Role created successfully.';
        }
    
        return redirect()->back()->with('success', $message);
    }
    
    /**
     * Store a newly created permission.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'permission_name' => 'required|string|max:255|unique:permissions,name',
        ]);

        Permission::create(['name' => $validated['permission_name']]);

        return redirect()->back()->with('success', 'Permission created successfully.');
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
        //
    }
}
