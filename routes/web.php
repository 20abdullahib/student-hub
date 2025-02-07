<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\DropboxController;
use App\Http\Controllers\Website\AboutController;
use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\ResourcesController;
use Illuminate\Support\Facades\Auth;
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


Route::prefix('dashboard/dropbox')->middleware('admin')->group(function () {
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

// Dashboard Main Routes
Route::resource('/dashboard', DashboardController::class)->middleware('admin');

// Auth Routes
Auth::routes();

Route::prefix('dashboard')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('dashboard.login.form');
    Route::post('/login', [LoginController::class, 'login'])->name('dashboard.login');
    Route::post('/logout', [LoginController::class, 'logout'])->name('dashboard.logout');
});

