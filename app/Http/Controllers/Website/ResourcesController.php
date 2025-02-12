<?php

namespace App\Http\Controllers\Website;

use App\Models\File;
use App\Models\Branch;
use App\Models\Subject;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class ResourcesController extends Controller
{
    public function index()
    {
        // Eager load files with their relationships and paginate the results
        $subjects = Subject::with(['files' => function ($query) {
            $query->orderBy('path'); // Order files by path for proper grouping
        }])->paginate(30); // Change 10 to however many subjects you want per page
    
        $departments = Department::all();
        $branches = Branch::all();
    
        return view('website.pages.resource.resources', compact('subjects', 'departments', 'branches'));
    }
    public function show($id, Request $request)
    {
        // Load the subject along with its files.
        $subject = Subject::with('files')->findOrFail($id);

        // Build a folder tree from the subject's files.
        // The second argument (true) tells the function to remove the first folder.
        $tree = $this->buildTree($subject->files, true);

        // Get the current folder path from the request.
        $currentFolderPath = $request->get('folder', '');

        // Retrieve the "current" node from the tree (the part corresponding to the current folder)
        $currentNode = $this->getCurrentNode($tree, $currentFolderPath);

        // Build breadcrumbs for navigation (optional).
        $breadcrumbs = $this->buildBreadcrumbs($subject->id, $currentFolderPath);

        // Load departments and branches for the header.
        $departments = Department::all();
        $branches = Branch::all();

        return view('website.pages.resource.partials.nested-folders', compact(
            'subject',
            'tree',
            'currentNode',
            'currentFolderPath',
            'breadcrumbs',
            'departments',
            'branches'
        ));
    }

    /**
     * Build a nested tree from a flat list of files.
     *
     * @param \Illuminate\Support\Collection $files
     * @param bool $removeFirstFolder  If true, the first folder (assumed to be the subject's folder) is removed.
     * @return array
     */
    private function buildTree($files, $removeFirstFolder = false)
    {
        $tree = [];
        foreach ($files as $file) {
            // Trim any leading/trailing slashes and whitespace.
            $trimmedPath = trim($file->path, "/ \t\n\r\0\x0B");
            if ($trimmedPath === '') {
                // File in the root (no folder)
                $tree['_files'][] = $file;
            } else {
                $parts = explode('/', $trimmedPath);

                // Optionally remove the first folder (subject folder)
                if ($removeFirstFolder && count($parts) > 0) {
                    array_shift($parts);
                }

                // If after removal no folder remains, file is at root.
                if (count($parts) === 0 || count($parts) === 1) {
                    $tree['_files'][] = $file;
                } else {
                    // The last element is the file name; the preceding elements are folder names.
                    $folderParts = array_slice($parts, 0, -1);
                    $current = &$tree;
                    foreach ($folderParts as $folder) {
                        if (!isset($current[$folder])) {
                            $current[$folder] = [];
                        }
                        $current = &$current[$folder];
                    }
                    if (!isset($current['_files'])) {
                        $current['_files'] = [];
                    }
                    $current['_files'][] = $file;
                }
            }
        }
        return $tree;
    }

    /**
     * Retrieve the node within the tree corresponding to the current folder path.
     *
     * @param array $tree
     * @param string $folderPath  e.g. "Subfolder1/Subfolder2"
     * @return array
     */
    private function getCurrentNode($tree, $folderPath)
    {
        $current = $tree;
        if ($folderPath !== '') {
            $parts = explode('/', $folderPath);
            foreach ($parts as $part) {
                if (isset($current[$part])) {
                    $current = $current[$part];
                } else {
                    // Folder not found—return an empty array.
                    return [];
                }
            }
        }
        return $current;
    }

    /**
     * Build breadcrumb data for navigation.
     *
     * @param int $subjectId
     * @param string $folderPath
     * @return array
     */
    private function buildBreadcrumbs($subjectId, $folderPath)
    {
        $breadcrumbs = [];
        // Home always links to the subject view with no folder
        $breadcrumbs[] = [
            'label' => 'Home',
            'url'   => route('resources.index', $subjectId)
        ];

        if ($folderPath !== '') {
            $parts = explode('/', $folderPath);
            $accumulated = '';
            foreach ($parts as $part) {
                $accumulated = $accumulated ? $accumulated . '/' . $part : $part;
                $breadcrumbs[] = [
                    'label' => $part,
                    'url'   => route('resources.subjects.show', $subjectId) . '?folder=' . urlencode($accumulated)
                ];
            }
        }
        return $breadcrumbs;
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
    
    public function preview($fileId)
    {
        $file = File::where('file_id', $fileId)->firstOrFail();

        if (!$file->rlkey) {
            abort(404, 'Shared link is not available for this file.');
        }

        // Construct the preview link
        $previewLink = "https://www.dropbox.com/scl/fi/{$file->file_id}?rlkey={$file->rlkey}&e=1&dl=0";
        return redirect($previewLink);
    }

    public function download($fileId)
    {
        $file = File::where('file_id', $fileId)->firstOrFail();

        if (!$file->rlkey) {
            abort(404, 'Shared link is not available for this file.');
        }

        // Construct the download link
        $downloadLink = "https://www.dropbox.com/scl/fi/{$file->file_id}?rlkey={$file->rlkey}&e=1&dl=1";
        return redirect($downloadLink);
    }


}
