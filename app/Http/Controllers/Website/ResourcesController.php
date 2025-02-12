<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Branch;
use App\Models\Subject;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ResourcesController extends Controller
{
    /**
     * Display a paginated list of subjects with their files.
     */
    public function index()
    {
        // Eager load files (ordering them by path) and paginate the subjects.
        $subjects = Subject::with(['files' => function ($query) {
            $query->orderBy('path');
        }])->paginate(30);

        $departments = Department::all();
        $branches    = Branch::all();

        return view('website.pages.resource.resources', compact('subjects', 'departments', 'branches'));
    }

    /**
     * Show the nested folder view for a given subject.
     *
     * @param int $id
     * @param Request $request
     */
    public function show(int $id, Request $request)
    {
        // Load the subject along with its files.
        $subject = Subject::with('files')->findOrFail($id);

        // Build the folder tree (removing the first folder if needed).
        $tree = $this->buildTree($subject->files, true);

        // Determine the current folder path (if any).
        $currentFolderPath = $request->get('folder', '');
        $currentNode       = $this->getCurrentNode($tree, $currentFolderPath);
        $breadcrumbs       = $this->buildBreadcrumbs($subject->id, $currentFolderPath);

        $departments = Department::all();
        $branches    = Branch::all();

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
     * @param Collection $files
     * @param bool $removeFirstFolder
     * @return array
     */
    private function buildTree(Collection $files, bool $removeFirstFolder = false): array
    {
        $tree = [];
        foreach ($files as $file) {
            $trimmedPath = trim($file->path, "/ \t\n\r\0\x0B");
            if ($trimmedPath === '') {
                // File is in the root directory.
                $tree['_files'][] = $file;
            } else {
                $parts = explode('/', $trimmedPath);

                if ($removeFirstFolder && count($parts) > 0) {
                    array_shift($parts);
                }

                // If no folder remains or just one part remains, treat the file as in the root.
                if (count($parts) === 0 || count($parts) === 1) {
                    $tree['_files'][] = $file;
                } else {
                    // Traverse or create the nested folder structure.
                    $folderParts = array_slice($parts, 0, -1);
                    $current     = &$tree;
                    foreach ($folderParts as $folder) {
                        if (!isset($current[$folder])) {
                            $current[$folder] = [];
                        }
                        $current = &$current[$folder];
                    }
                    $current['_files'][] = $file;
                }
            }
        }

        return $tree;
    }

    /**
     * Retrieve the tree node corresponding to the current folder path.
     *
     * @param array $tree
     * @param string $folderPath
     * @return array
     */
    private function getCurrentNode(array $tree, string $folderPath): array
    {
        $current = $tree;
        if ($folderPath !== '') {
            $parts = explode('/', $folderPath);
            foreach ($parts as $part) {
                if (isset($current[$part])) {
                    $current = $current[$part];
                } else {
                    return []; // Folder not found.
                }
            }
        }

        return $current;
    }

    /**
     * Build breadcrumb links for navigation.
     *
     * @param int $subjectId
     * @param string $folderPath
     * @return array
     */
    private function buildBreadcrumbs(int $subjectId, string $folderPath): array
    {
        $breadcrumbs = [
            [
                'label' => 'Resources',
                'url'   => route('resources.index', $subjectId)
            ]
        ];

        if ($folderPath !== '') {
            $parts       = explode('/', $folderPath);
            $accumulated = '';
            foreach ($parts as $part) {
                $accumulated .= ($accumulated ? '/' : '') . $part;
                $breadcrumbs[] = [
                    'label' => $part,
                    'url'   => route('resources.subjects.show', $subjectId) . '?folder=' . urlencode($accumulated)
                ];
            }
        }

        return $breadcrumbs;
    }

    /**
     * Return subject suggestions based on a query.
     */
    public function getSuggestions(Request $request)
    {
        $query = $request->input('query');
        $subjects = Subject::where('name', 'LIKE', "%{$query}%")
            ->orWhere('code', 'LIKE', "%{$query}%")
            ->get(['name', 'code']);

        return response()->json($subjects);
    }

    /**
     * Search subjects by name or code.
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $subjects = Subject::where('name', 'LIKE', "%{$query}%")
            ->orWhere('code', 'LIKE', "%{$query}%")
            ->get();

        if ($request->expectsJson()) {
            return response()->json($subjects);
        }

        $departments = DB::table('departments')->get();
        $branches    = DB::table('branches')->get();

        return view('website.pages.resource.resources', compact('subjects', 'departments', 'branches', 'query'));
    }

    /**
     * Filter subjects based on department, branch, and sort options.
     */
    public function filterData(Request $request)
    {
        $query = Subject::query();

        if ($request->filled('department')) {
            $query->where('department_id', $request->department);
        }

        if ($request->filled('branch')) {
            $query->whereHas('branches', function ($q) use ($request) {
                $q->where('branch_id', $request->branch);
            });
        }

        if ($request->filled('sort')) {
            $query->orderBy('created_at', $request->sort === 'Newest' ? 'desc' : 'asc');
        }

        $subjects = $query->get();

        if ($request->ajax()) {
            return response()->json($subjects);
        }

        $departments = DB::table('departments')->get();
        $branches    = DB::table('branches')->get();

        return view('website.pages.resource.resources', compact('subjects', 'departments', 'branches'));
    }

    /**
     * Filter subjects by department and optional branch.
     *
     * @param int $department
     * @param int|null $branch
     */
    public function filterDataDepartmentBranch(int $department, ?int $branch = null)
    {
        $departmentData = Department::findOrFail($department);
        $departments    = DB::table('departments')->get();
        $branches       = DB::table('branches')->get();
        $subjects       = collect();

        if ($branch) {
            $branchData = $departmentData->branches()->findOrFail($branch);
            $subjects   = $branchData->subjects;
            $query      = $departmentData->branches()->where('id', $branch)->pluck('name')->first();
        } else {
            $subjects = Subject::where('department_id', $department)->get();
            $query    = $departmentData->name;
        }

        return view('website.pages.resource.resources', compact('subjects', 'departments', 'branches', 'query'));
    }

    /**
     * Redirect to the Dropbox preview URL for a file.
     *
     * @param string $fileId
     */
    public function preview(string $fileId)
    {
        $file = File::where('file_id', $fileId)->firstOrFail();
        return redirect($this->getDropboxLink($file, 0));
    }

    /**
     * Redirect to the Dropbox download URL for a file.
     *
     * @param string $fileId
     */
    public function download(string $fileId)
    {
        $file = File::where('file_id', $fileId)->firstOrFail();
        return redirect($this->getDropboxLink($file, 1));
    }

    /**
     * Build the Dropbox URL for preview or download.
     *
     * @param File $file
     * @param int $downloadFlag 0 for preview, 1 for download.
     * @return string
     */
    private function getDropboxLink(File $file, int $downloadFlag): string
    {
        if (!$file->rlkey) {
            abort(404, 'Shared link is not available for this file.');
        }

        return "https://www.dropbox.com/scl/fi/{$file->file_id}?rlkey={$file->rlkey}&e=1&dl={$downloadFlag}";
    }
}
