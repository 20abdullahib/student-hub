<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\AboutController;
use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\DropboxController;
use App\Http\Controllers\Website\ResourcesController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\PermissionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', [HomeController::class, 'index'])->name('home.index');

// Route::get('/about-teem', [AboutController::class, 'index'])->name('about.index');

// Route::get('/about-teem/generation/{year}', [AboutController::class, 'showGeneration'])->name('about.showGeneration');

// Route::get('/resources', [ResourcesController::class, 'index'])->name('resources.index');

// Route::get('/resources/suggestions', [ResourcesController::class, 'getSuggestions']);

// Route::get('/resources/search', [ResourcesController::class, 'search'])->name('resources.search');

// Route::get('/resources/filter', [ResourcesController::class, 'filterData'])->name('resources.filter');

// Route::get('/resources/filter/{department}/{branch?}', [ResourcesController::class, 'filterDataDepartmentBranch'])
//     ->name('resources.filter.departmentbranch');


// // Dashboard Routes 


// // Dropbox Routes
// Route::prefix('dashboard/dropbox')->middleware(['auth:admin', 'session.timeout'])->group(function () {
//     // Forms
//     Route::get('/account', [DropboxController::class, 'showForm'])->name('dropbox.account.form');
//     Route::get('/upload', [DropboxController::class, 'showUploadForm'])->name('dropbox.upload.form');

//     // Account Operations
//     Route::get('/accounts', [DropboxController::class, 'listAccounts'])->name('dropbox.account.index');
//     Route::post('/account/setup', [DropboxController::class, 'setupAccount'])->name('dropbox.account.setup');
//     Route::post('/account/update', [DropboxController::class, 'updateDropbox'])->name('dropbox.account.update');
//     Route::delete('/account/{id}', [DropboxController::class, 'deleteAccount'])->name('dropbox.account.delete');

//     // File Operations
//     Route::post('/files/store-details', [DropboxController::class, 'storeFileDetails'])->name('dropbox.files.store');
//     Route::get('/files', [DropboxController::class, 'listFiles'])->name('dropbox.files.index');
//     Route::delete('/files/{file}', [DropboxController::class, 'deleteFiles'])->name('dropbox.files.delete');
//     Route::get('/files/accounts', [DropboxController::class, 'getAccountForUpload'])->name('dropbox.files.accounts');
// });

// // API Endpoints
// Route::prefix('dropbox')->group(function () {
//     Route::get('/access-token', [DropboxController::class, 'getAccessToken'])->name('dropbox.api.token');
//     Route::post('/refresh-tokens', [DropboxController::class, 'refreshAllTokens'])->name('dropbox.api.refresh');
//     Route::get('/files/{departmentId}', [DropboxController::class, 'showFiles'])->name('dropbox.api.files');
// });

// // Dashboard Main Routes

// // Admin Routes
// Route::middleware(['auth:admin', 'session.timeout'])->group(function () {
//     Route::prefix('dashboard')->group(function () {
//         Route::resource('admin', AdminController::class);
//         Route::resource('permission', PermissionController::class);
//         Route::post('permission/role', [PermissionController::class, 'storeRole'])->name('permission.store.role');
//         Route::patch('admin/{admin}/update-role', [AdminController::class, 'updateRole'])->name('admin.updateRole');
//     });

//     Route::get('admin/search-suggestions', [AdminController::class, 'searchSuggestions'])->name('admin.search-suggestions');
//     Route::resource('/dashboard', DashboardController::class);
// });

// // Auth Routes
// Auth::routes();

// Route::prefix('dashboard')->group(function () {
//     Route::get('/login', [LoginController::class, 'showLoginForm'])->name('dashboard.login.form');
//     Route::post('/login', [LoginController::class, 'login'])->name('dashboard.login');
//     Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:admin')->name('dashboard.logout');
// });


// ======================================================================================


/*
|--------------------------------------------------------------------------
| Website Routes
|--------------------------------------------------------------------------
*/

// Route::name('home.')
//     ->group(function () {
//         Route::get('/', [HomeController::class, 'index'])->name('index');
//     });

// Route::prefix('about-teem')
//     ->name('about.')
//     ->group(function () {
//         Route::get('/', [AboutController::class, 'index'])->name('index');
//         Route::get('/generation/{year}', [AboutController::class, 'showGeneration'])->name('showGeneration');
//     });

// Route::prefix('resources')
//     ->name('resources.')
//     ->group(function () {
//         Route::get('/', [ResourcesController::class, 'index'])->name('index');
//         Route::get('/suggestions', [ResourcesController::class, 'getSuggestions'])->name('suggestions');
//         Route::get('/search', [ResourcesController::class, 'search'])->name('search');
//         Route::get('/filter', [ResourcesController::class, 'filterData'])->name('filter');
//         Route::get('/filter/{department}/{branch?}', [ResourcesController::class, 'filterDataDepartmentBranch'])
//             ->name('filter.departmentbranch');

//         Route::get('/dropbox-tree', [ResourcesController::class, 'showDropboxTree'])
//             ->name('dropbox.tree');
//     });

// /*
// |--------------------------------------------------------------------------
// | Dropbox Routes (Dashboard)
// |--------------------------------------------------------------------------
// |
// | These routes are available only to authenticated admins and are used
// | for account and file operations in Dropbox.
// |
// */
// Route::prefix('dashboard/dropbox')
//     ->middleware(['auth:admin', 'session.timeout'])
//     ->name('dropbox.')
//     ->group(function () {
//         // Forms
//         Route::get('/account', [DropboxController::class, 'showForm'])->name('account.form');
//         Route::get('/upload', [DropboxController::class, 'showUploadForm'])->name('upload.form');

//         // Account Operations
//         Route::get('/accounts', [DropboxController::class, 'listAccounts'])->name('account.index');
//         Route::post('/account/setup', [DropboxController::class, 'setupAccount'])->name('account.setup');
//         Route::post('/account/update', [DropboxController::class, 'updateDropbox'])->name('account.update');
//         Route::delete('/account/{id}', [DropboxController::class, 'deleteAccount'])->name('account.delete');

//         // File Operations
//         Route::post('/files/store-details', [DropboxController::class, 'storeFileDetails'])->name('files.store');
//         Route::get('/files', [DropboxController::class, 'listFiles'])->name('files.index');
//         Route::delete('/files/{file}', [DropboxController::class, 'deleteFiles'])->name('files.delete');
//         Route::get('/files/accounts', [DropboxController::class, 'getAccountForUpload'])->name('files.accounts');
//     });

// /*
// |--------------------------------------------------------------------------
// | Dropbox API Endpoints
// |--------------------------------------------------------------------------
// |
// | These endpoints (which could later be moved to an API route file if needed)
// | provide access to Dropbox functionality.
// |
// */
// Route::prefix('dropbox')
//     ->name('dropbox.api.')
//     ->group(function () {
//         Route::get('/access-token', [DropboxController::class, 'getAccessToken'])->name('token');
//         Route::post('/refresh-tokens', [DropboxController::class, 'refreshAllTokens'])->name('refresh');
//         Route::get('/files/{departmentId}', [DropboxController::class, 'showFiles'])->name('files');
//     });

// /*
// |--------------------------------------------------------------------------
// | Dashboard & Admin Routes
// |--------------------------------------------------------------------------
// |
// | Routes below are protected by the admin auth and session timeout middlewares.
// | The main dashboard (at /dashboard) and administration functions (at
// | /dashboard/admin and /dashboard/permission) are defined here.
// |
// */
// Route::middleware(['auth:admin', 'session.timeout'])->group(function () {
//     // Main Dashboard routes (e.g. /dashboard, /dashboard/{dashboard}/edit, etc.)
//     Route::resource('/dashboard', DashboardController::class)
//         ->names('dashboard');

//     // Admin & Permission management under a dashboard prefix
//     Route::prefix('dashboard')->group(function () {
//         // Admin resource routes
//         Route::resource('admin', AdminController::class)
//             ->names('admin');
//         Route::patch('admin/{admin}/update-role', [AdminController::class, 'updateRole'])
//             ->name('admin.updateRole');
//         // Optionally, if you prefer the search-suggestions route under the same prefix,
//         // change the URL to /dashboard/admin/search-suggestions.
//         Route::get('admin/search-suggestions', [AdminController::class, 'searchSuggestions'])
//             ->name('admin.search-suggestions');

//         // Permission resource routes
//         Route::resource('permission', PermissionController::class)
//             ->names('permission');
//         Route::post('permission/role', [PermissionController::class, 'storeRole'])
//             ->name('permission.store.role');
//     });
// });

// /*
// |--------------------------------------------------------------------------
// | Authentication Routes
// |--------------------------------------------------------------------------
// */
// Auth::routes();

// Route::prefix('dashboard')
//     ->name('dashboard.')
//     ->group(function () {
//         Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
//         Route::post('/login', [LoginController::class, 'login'])->name('login');
//         Route::post('/logout', [LoginController::class, 'logout'])
//             ->middleware('auth:admin')
//             ->name('logout');
//     });



// ============================================================================



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
    Route::get('/suggestions', [ResourcesController::class, 'getSuggestions']);
    Route::get('/search', [ResourcesController::class, 'search'])->name('resources.search');
    Route::get('/filter', [ResourcesController::class, 'filterData'])->name('resources.filter');
    Route::get('/filter/{department}/{branch?}', [ResourcesController::class, 'filterDataDepartmentBranch'])
        ->name('resources.filter.departmentbranch');

    Route::get('/file/preview/{fileId}', [ResourcesController::class, 'preview'])->name('file.preview');
    Route::get('/file/download/{fileId}', [ResourcesController::class, 'download'])->name('file.download');
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
