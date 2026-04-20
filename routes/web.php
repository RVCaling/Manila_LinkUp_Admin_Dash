<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SeekerController;
use App\Http\Controllers\Admin\EmployerController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\ProfileController; // <--- ADDED THIS

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// --- Admin Authentication Routes ---
Route::get('/admin/login', [AdminDashboardController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminDashboardController::class, 'authenticate'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminDashboardController::class, 'logout'])->name('admin.logout');

// --- Protected Admin Management Routes ---
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // User Database
    Route::get('/users', [UserController::class, 'index'])->name('users');

    // Seeker Verification
    Route::get('/seekers', [SeekerController::class, 'index'])->name('seekers');
    
    // Employer Verification
    Route::get('/employers', [EmployerController::class, 'index'])->name('employers');

    // Analytics
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');

    // --- Admin Profile Routes ---
    // 1. Route to view the profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    
    // 2. Route to handle the form submission (Update)
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});