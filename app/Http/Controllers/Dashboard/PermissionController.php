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
        $validated = $request->validate([
            'role_name'       => 'required|string|max:255|unique:roles,name',
            'role_permissions' => 'nullable|array',
            'role_permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create(['name' => $validated['role_name']]);

        if (!empty($validated['role_permissions'])) {
            $role->syncPermissions($validated['role_permissions']);
        }

        return redirect()->back()->with('success', 'Role created successfully.');
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
