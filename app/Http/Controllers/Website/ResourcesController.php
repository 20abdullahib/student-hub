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
        $subjects = $this->getSubjectsQuery()->paginate(30);

        return view('website.pages.resource.resources', array_merge([
            'subjects' => $subjects,
        ], $this->getDepartmentAndBranchData()));
    }

    /**
     * Show the nested folder view for a given subject.
     *
     * @param int $id
     * @param Request $request
     */
    public function show(int $id, Request $request)
    {
        $subject = Subject::with('files')->findOrFail($id);
        $tree = $this->buildTree($subject->files, true);

        $currentFolderPath = $request->get('folder', '');
        $currentNode       = $this->getCurrentNode($tree, $currentFolderPath);
        $breadcrumbs       = $this->buildBreadcrumbs($subject->id, $currentFolderPath);

        return view('website.pages.resource.partials.nested-folders', array_merge([
            'subject'           => $subject,
            'tree'              => $tree,
            'currentNode'       => $currentNode,
            'currentFolderPath' => $currentFolderPath,
            'breadcrumbs'       => $breadcrumbs,
        ], $this->getDepartmentAndBranchData()));
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

            // File is in the root directory.
            if ($trimmedPath === '') {
                $tree['_files'][] = $file;
                continue;
            }

            $parts = explode('/', $trimmedPath);

            if ($removeFirstFolder && count($parts) > 0) {
                array_shift($parts);
            }

            // If no folder remains or just one part remains, treat the file as in the root.
            if (count($parts) <= 1) {
                $tree['_files'][] = $file;
                continue;
            }

            // Build nested folder structure.
            $folderParts = array_slice($parts, 0, -1);
            $current = &$tree;

            foreach ($folderParts as $folder) {
                if (!isset($current[$folder])) {
                    $current[$folder] = [];
                }
                $current = &$current[$folder];
            }

            $current['_files'][] = $file;
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
            foreach (explode('/', $folderPath) as $part) {
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
                'url'   => route('resources.index', $subjectId),
            ],
        ];

        if ($folderPath !== '') {
            $accumulated = '';
            foreach (explode('/', $folderPath) as $part) {
                $accumulated .= ($accumulated ? '/' : '') . $part;
                $breadcrumbs[] = [
                    'label' => $part,
                    'url'   => route('resources.subjects.show', $subjectId) . '?folder=' . urlencode($accumulated),
                ];
            }
        }

        return $breadcrumbs;
    }

    /**
     * Search subjects by name or code and return suggestions if requested.
     */
    public function search(Request $request)
    {
        $query = $request->input('query', '');
        
        $subjects = $this->getSubjectsQuery($query)->paginate(20);

        return $this->handleResponse($request, $subjects, ['query' => $query]);
    }

    /**
     * Get search suggestions for autocomplete.
     */
    public function suggestions(Request $request)
    {
        $query = $request->input('query', '');
        
        if (empty($query)) {
            // For testing, return some subjects even without query
            $subjects = Subject::whereHas('files')
                ->select('id', 'name', 'code', 'description')
                ->limit(5)
                ->get();
                
            return response()->json([
                'data' => $subjects,
                'count' => $subjects->count(),
                'message' => 'No query provided - showing sample subjects'
            ]);
        }

        $subjects = Subject::where(function($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%")
              ->orWhere('code', 'LIKE', "%{$query}%");
        })
        ->whereHas('files')
        ->select('id', 'name', 'code', 'description')
        ->limit(10)
        ->get();

        return response()->json([
            'data' => $subjects,
            'count' => $subjects->count(),
            'query' => $query
        ]);
    }

    /**
     * Filter subjects based on department, branch, and search query.
     */
    public function filterData(Request $request)
    {
        $subjects = $this->getSubjectsQuery(
            $request->input('query', ''),
            $request->input('department'),
            $request->input('branch')
        )->paginate(20);

        return $this->handleResponse($request, $subjects);
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

    /**
     * Retrieve common data for departments and branches.
     *
     * @return array
     */
    private function getDepartmentAndBranchData(): array
    {
        return [
            'departments' => Department::all(),
            'branches'    => Branch::all(),
        ];
    }

    /**
     * Build a reusable query for subjects with files.
     * 
     * @param string|null $searchQuery
     * @param string|null $departmentId
     * @param string|null $branchId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function getSubjectsQuery($searchQuery = null, $departmentId = null, $branchId = null)
    {
        $query = Subject::with(['files' => function ($q) {
            $q->orderBy('path');
        }])
        ->whereHas('files')
        ->withCount('files');

        // Apply search filter
        if (!empty($searchQuery)) {
            $query->where(function($q) use ($searchQuery) {
                $q->where('name', 'LIKE', "%{$searchQuery}%")
                  ->orWhere('code', 'LIKE', "%{$searchQuery}%");
            });
        }

        // Apply department filter
        if (!empty($departmentId)) {
            $query->where('department_id', $departmentId);
        }

        // Apply branch filter
        if (!empty($branchId)) {
            $query->whereHas('branches', function($q) use ($branchId) {
                $q->where('branches.id', $branchId);
            });
        }

        // Default sorting by name
        $query->orderBy('name', 'asc');

        return $query;
    }

    /**
     * Handle the response for both AJAX and regular requests.
     * 
     * @param Request $request
     * @param \Illuminate\Contracts\Pagination\LengthAwarePaginator $subjects
     * @param array $extraData
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    private function handleResponse(Request $request, $subjects, array $extraData = [])
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'data' => $subjects->items(),
                'pagination' => $subjects->appends($request->all())->render(),
                'total' => $subjects->total(),
                'current_page' => $subjects->currentPage(),
                'last_page' => $subjects->lastPage(),
            ]);
        }

        return view('website.pages.resource.resources', array_merge([
            'subjects' => $subjects,
        ], $extraData, $this->getDepartmentAndBranchData()));
    }
}
