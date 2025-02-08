<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Admin;
use App\Models\Branch;
use App\Models\Department;
use Illuminate\Http\Request;
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
    
        return view('dashboard.pages.admin.admins', compact('admins' , 'branches', 'departments'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
