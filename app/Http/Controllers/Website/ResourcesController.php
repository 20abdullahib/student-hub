<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class ResourcesController extends Controller
{
    public function index()
    {
        $subjects = DB::table('subjects')->get();
        $departments = DB::table('departments')->get();
        $branches = DB::table('branches')->get();
        return view('website.pages.resource.resources', compact('subjects', 'departments', 'branches'));
    }

    // public function show($subject)
    // {
    //     return view('website.pages.resources.show', ['subject' => $subject]);
    // }

    public function getSuggestions(Request $request)
    {
        $query = $request->input('query');

        // Fetch subjects based on name or code
        $subjects = Subject::where('name', 'LIKE', "%{$query}%")
            ->orWhere('code', 'LIKE', "%{$query}%")
            ->get(['name', 'code']); // Return only necessary fields

        return response()->json($subjects); // Return suggestions as JSON
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        // Search for subjects by name or code
        $subjects = Subject::where('name', 'LIKE', "%{$query}%")
            ->orWhere('code', 'LIKE', "%{$query}%")
            ->get();



        // Check if the request expects JSON (for AJAX/live search)
        if ($request->expectsJson()) {
            return response()->json($subjects);
        } else {

            $departments = DB::table('departments')->get();
            $branches = DB::table('branches')->get();
            // If it's a standard request, return resources.index view with subjects data
            return view('website.pages.resource.resources', ['subjects' => $subjects], compact('departments', 'branches', 'query'));
        }
    }



    public function filterData(Request $request)
    {
        $query = Subject::query();

        // Apply filters if they exist
        if ($request->filled('department')) {
            $query->where('department_id', $request->department);
        }

        if ($request->filled('branch')) {
            // Use whereHas to filter subjects that are associated with the specified branch
            $query->whereHas('branches', function ($query) use ($request) {
                $query->where('branch_id', $request->branch);
            });
        }

        // Apply sorting
        if ($request->filled('sort')) {
            if ($request->sort == 'Newest') {
                $query->orderBy('created_at', 'desc');
            } elseif ($request->sort == 'Oldest') {
                $query->orderBy('created_at', 'asc');
            }
        }

        // Fetch filtered results
        $subjects = $query->get();

        // Check if the request is AJAX
        if ($request->ajax()) {
            // Return JSON response for AJAX request
            return response()->json($subjects);
        } else {
            // Return view for regular requests with filtered data
            $departments = DB::table('departments')->get();
            $branches = DB::table('branches')->get();

            return view('website.pages.resource.resources', compact('subjects', 'departments', 'branches'));
        }
    }

    public function filterDataDepartmentBranch($department, $branch = null)
    {
        // Fetch the department by ID
        $departmentData = Department::findOrFail($department);
        $departments = DB::table('departments')->get();
        $branches = DB::table('branches')->get();
        
        // Prepare the subjects collection
        $subjects = collect();
    
        if ($branch) {
            // If a branch is provided, fetch the specific branch
            $branchData = $departmentData->branches()->findOrFail($branch);
            // Get subjects related to the specific branch
            $subjects = $branchData->subjects;
            $query = $departmentData->branches()->where('id', $branch)->pluck('name')->first();
            return view('website.pages.resource.resources', compact('subjects', 'departments', 'branches', 'query'));
        }
    
        // If no branch is provided, get subjects directly related to the department
        $subjects = Subject::where('department_id', $department)->get();
        
        $query = $departmentData->name;

        return view('website.pages.resource.resources', compact( 'subjects', 'departments', 'branches', 'query'));
    }
    


}
