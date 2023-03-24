<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\Listing\JobController;
use App\Http\Controllers\Listing\ServiceController;
use App\Http\Controllers\Listing\StateController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TicketController;
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

Route::get('/', [HomepageController::class, 'index'])->name('index');

Route::get('/test', function () {
    return view('test');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::resource('companies', CompanyController::class);
    Route::resource('users', UserController::class);
    Route::resource('tickets', TicketController::class);
    Route::resource('roles', RoleController::class);
    Route::post('comment/{ticket}', [CommentController::class, 'store'])->name('comments.store');

    // Listing
    Route::resource('jobs', JobController::class);
    Route::resource('states', StateController::class);
    Route::resource('services', ServiceController::class);
});

require __DIR__.'/auth.php';
