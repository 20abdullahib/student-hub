<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\AboutController;
use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\DropboxController;
use App\Http\Controllers\Website\ResourcesController;
use App\Http\Controllers\Dashboard\SettingsController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\PermissionController;
use App\Http\Controllers\Dashboard\TeamMemberController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home.index');

Route::prefix('about-teem')->group(function () {
    Route::get('/', [AboutController::class, 'index'])->name('about.index');
    Route::get('/generation/{year}', [AboutController::class, 'showGeneration'])->name('about.showGeneration');
});

Route::prefix('resources')->group(function () {
    Route::get('/', [ResourcesController::class, 'index'])->name('resources.index');
    Route::get('/search', [ResourcesController::class, 'search'])->name('resources.search');
    Route::get('/suggestions', [ResourcesController::class, 'suggestions'])->name('resources.suggestions');
    Route::get('/filter', [ResourcesController::class, 'filterData'])->name('resources.filter');

    Route::get('/file/download/{fileId}', [ResourcesController::class, 'download'])->name('file.download');
    Route::get('/file/preview/{fileId}', [ResourcesController::class, 'preview'])->name('file.preview');
    Route::get('/{id}', [ResourcesController::class, 'show'])->name('resources.subjects.show');
});

/*
|--------------------------------------------------------------------------
| Dashboard Dropbox Routes
|--------------------------------------------------------------------------
*/
Route::prefix('dashboard/dropbox')
    ->middleware(['auth:admin', 'session.timeout'])
    ->group(function () {
        // Forms
        Route::get('/account', [DropboxController::class, 'showForm'])->name('dropbox.account.form');
        Route::get('/upload', [DropboxController::class, 'showUploadForm'])->name('dropbox.upload.form');

        // Account Operations
        Route::get('/accounts', [DropboxController::class, 'listAccounts'])->name('dropbox.account.index');
        Route::post('/account/setup', [DropboxController::class, 'setupAccount'])->name('dropbox.account.setup');
        Route::post('/account/update', [DropboxController::class, 'updateDropbox'])->name('dropbox.account.update');
        Route::delete('/account/{id}', [DropboxController::class, 'deleteAccount'])->name('dropbox.account.delete');

        // File Operations
        Route::post('/files/store-details', [DropboxController::class, 'storeFileDetails'])->name('dropbox.files.store');
        Route::get('/files', [DropboxController::class, 'listFiles'])->name('dropbox.files.index');
        Route::delete('/files/{file}', [DropboxController::class, 'deleteFiles'])->name('dropbox.files.delete');
        Route::get('/files/accounts', [DropboxController::class, 'getAccountForUpload'])->name('dropbox.files.accounts');
    });
/*
|--------------------------------------------------------------------------
| Dropbox API Endpoints
|--------------------------------------------------------------------------
*/
Route::prefix('dropbox')->group(function () {
    Route::get('/access-token', [DropboxController::class, 'getAccessToken'])->name('dropbox.api.token');
    Route::post('/refresh-tokens', [DropboxController::class, 'refreshAllTokens'])->name('dropbox.api.refresh');
    Route::get('/files/{departmentId}', [DropboxController::class, 'showFiles'])->name('dropbox.api.files');
});

/*
|--------------------------------------------------------------------------
| Dashboard Settings Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:admin', 'session.timeout'])
    ->group(function () {
        Route::resource('/settings/team-members', TeamMemberController::class);
        Route::resource('/settings', SettingsController::class);
    });

/*
|--------------------------------------------------------------------------
| Admin and Dashboard Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:admin', 'session.timeout'])->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::resource('admin', AdminController::class);
        Route::resource('permission', PermissionController::class);
        Route::post('permission/role', [PermissionController::class, 'storeRole'])->name('permission.store.role');
        Route::patch('admin/{admin}/update-role', [AdminController::class, 'updateRole'])->name('admin.updateRole');
    });

    Route::get('admin/search-suggestions', [AdminController::class, 'searchSuggestions'])->name('admin.search-suggestions');
    Route::resource('/dashboard', DashboardController::class);
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Auth::routes();

Route::prefix('dashboard')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('dashboard.login.form');
    Route::post('/login', [LoginController::class, 'login'])->name('dashboard.login');
    Route::post('/logout', [LoginController::class, 'logout'])
        ->middleware('auth:admin')
        ->name('dashboard.logout');
});
