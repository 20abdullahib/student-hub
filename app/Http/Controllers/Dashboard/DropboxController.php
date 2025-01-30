<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\DropboxAccount;
use App\Models\File;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Services\DropboxService;

class DropboxController extends Controller
{
    protected $dropboxService;

    public function __construct(DropboxService $dropboxService)
    {
        $this->dropboxService = $dropboxService;
    }

    public function showForm()
    {
        return view('dashboard.pages.dropbox.AddNewAccount', [
            'departments' => Department::all()
        ]);
    }

    public function showUploadForm()
    {
        return view('dashboard.pages.dropbox.UplaodFiles', [
            'departments' => Department::all(),
            'subjects' => Subject::all(),
        ]);
    }

    public function setupAccount(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'client_id' => 'required|string',
            'client_secret' => 'required|string',
            'refresh_token' => 'required|string',
            'department_id' => 'required|exists:departments,id',
        ]);

        if (!$this->dropboxService->verifyCredentials($validated)) {
            return redirect()->back()->with('error', 'Invalid Dropbox credentials');
        }

        DropboxAccount::updateOrCreate(
            ['email' => $validated['email']],
            $validated
        );

        return redirect()->back()->with('success', 'Account setup successful');
    }

    public function refreshAllTokens()
    {
        DropboxAccount::all()->each(function ($account) {
            $this->dropboxService->refreshAccessToken($account);
        });

        return response()->json(['message' => 'Tokens refreshed successfully']);
    }

    public function storeFileDetails(Request $request)
    {
        File::create($request->validate([
            'name' => 'required|string',
            'path' => 'required|string',
            'size' => 'required|integer',
            'subject_id' => 'required|exists:subjects,id',
            'dropbox_account_id' => 'required|exists:dropbox_accounts,id',
            'link' => 'required|string',
            'file_id' => 'required|string',
        ]));

        return response()->json(['message' => 'File details saved']);
    }

    public function updateDropbox(Request $request)
    {
        DropboxAccount::findOrFail($request->account_id)
            ->update(['remaining_storage' => $request->remaining_storage]);

        return response()->json(['message' => 'Storage updated']);
    }

    public function showFiles($departmentId)
    {
        $department = Department::with('dropboxAccounts')->findOrFail($departmentId);
        
        $fileLinks = $department->dropboxAccounts->flatMap(function ($account) {
            $this->dropboxService->ensureValidToken($account);
            return $this->dropboxService->getAccountFiles($account);
        });

        return view('website.pages.resource.resources', ['fileLinks' => $fileLinks]);
    }

    public function getAccessToken(Request $request)
    {
        $account = DropboxAccount::findOrFail($request->account_id);
        $this->dropboxService->ensureValidToken($account);

        return response()->json(['access_token' => $account->access_token]);
    }

    public function getAccountForUpload(Request $request)
    {
        $subject = Subject::findOrFail($request->subject_id);
        
        $accounts = DropboxAccount::where('department_id', $subject->department_id)
            ->get()
            ->map(function ($account) {
                $this->dropboxService->ensureValidToken($account);
                return $account->only(['id', 'name', 'department_id']);
            });

        return response()->json($accounts);
    }
}