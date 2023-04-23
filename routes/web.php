<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\Listing\HourlyRateController;
use App\Http\Controllers\Listing\JobController;
use App\Http\Controllers\Listing\ServiceController;
use App\Http\Controllers\Listing\StateController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Ticket\EditVisibilityStateController;
use App\Http\Controllers\Ticket\TicketController;
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

// Auth
Route::middleware(['auth', 'verified'])->group(function () {

    // Ticket
    Route::resource('tickets', TicketController::class);
    Route::patch('/tickets/{ticket}/udpate-visibility-state', [EditVisibilityStateController::class, 'update'])->name('tickets.updateVisibilityState');
    
    // Comment
    Route::patch('/tickets/comment/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/tickets/comment/{comment}/delete', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/tickets/{ticket}/comment', [CommentController::class, 'store'])->name('comments.store');
    
    // Company
    Route::resource('companies', CompanyController::class);
    
    // User
    Route::resource('users', UserController::class);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Role 
    Route::resource('roles', RoleController::class);
    
    // Listing
    Route::resource('jobs', JobController::class);
    Route::resource('states', StateController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('hourly_rate', HourlyRateController::class);
    
    // Review 
    Route::resource('reviews', ReviewController::class);
});

require __DIR__.'/auth.php';
