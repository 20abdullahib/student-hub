<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\DropboxController;
use App\Http\Controllers\Website\AboutController;
use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\ResourcesController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [HomeController::class, 'index'])->name('home.index');

Route::get('/about-teem', [AboutController::class, 'index'])->name('about.index');

Route::get('/about-teem/generation/{year}', [AboutController::class, 'showGeneration'])->name('about.showGeneration');

Route::get('/resources', [ResourcesController::class, 'index'])->name('resources.index');

Route::get('/resources/suggestions', [ResourcesController::class, 'getSuggestions']);

Route::get('/resources/search', [ResourcesController::class, 'search'])->name('resources.search');

Route::get('/resources/filter', [ResourcesController::class, 'filterData'])->name('resources.filter');

Route::get('/resources/filter/{department}/{branch?}', [ResourcesController::class, 'filterDataDepartmentBranch'])
    ->name('resources.filter.departmentbranch');


// Dashboard Routes 



// Dropbox Routes
Route::prefix('dashboard/dropbox')->group(function () {
    // Forms
    Route::get('/account', [DropboxController::class, 'showForm'])->name('dropbox.account.form');
    Route::get('/upload', [DropboxController::class, 'showUploadForm'])->name('dropbox.upload.form');
    
    // Account Operations
    Route::post('/account/setup', [DropboxController::class, 'setupAccount'])->name('dropbox.account.setup');
    Route::post('/account/update', [DropboxController::class, 'updateDropbox'])->name('dropbox.account.update');
    
    // File Operations
    Route::post('/files/store-details', [DropboxController::class, 'storeFileDetails'])->name('dropbox.files.store');
    Route::get('/files/accounts', [DropboxController::class, 'getAccountForUpload'])->name('dropbox.files.accounts');
});

// API Endpoints
Route::prefix('dropbox')->group(function () {
    Route::get('/access-token', [DropboxController::class, 'getAccessToken'])->name('dropbox.api.token');
    Route::post('/refresh-tokens', [DropboxController::class, 'refreshAllTokens'])->name('dropbox.api.refresh');
    Route::get('/files/{departmentId}', [DropboxController::class, 'showFiles'])->name('dropbox.api.files');
});

// Dashboard Main Routes
Route::resource('/dashboard', DashboardController::class);
