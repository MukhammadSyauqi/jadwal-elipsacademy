<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminJadwalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperadminDashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard if logged in, otherwise to login
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        return $user->role === 'superadmin'
            ? redirect()->route('superadmin.dashboard')
            : redirect()->route('admin.dashboard');
    }

    return redirect()->route('login');
});

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Admin & Superadmin can access admin area
    Route::middleware('role:admin,superadmin')->group(function () {
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        // CRUD Jadwal
        Route::get('/admin/jadwal/create', [AdminJadwalController::class, 'create'])->name('admin.jadwal.create');
        Route::post('/admin/jadwal', [AdminJadwalController::class, 'store'])->name('admin.jadwal.store');
        Route::get('/admin/jadwal/{jadwal}', [AdminJadwalController::class, 'show'])->name('admin.jadwal.show');
        Route::get('/admin/jadwal/{jadwal}/edit', [AdminJadwalController::class, 'edit'])->name('admin.jadwal.edit');
        Route::put('/admin/jadwal/{jadwal}', [AdminJadwalController::class, 'update'])->name('admin.jadwal.update');
        Route::post('/admin/jadwal/{jadwal}/batal', [AdminJadwalController::class, 'batal'])->name('admin.jadwal.batal');
        Route::delete('/admin/jadwal/{jadwal}', [AdminJadwalController::class, 'destroy'])->name('admin.jadwal.destroy');
    });

    // Only Superadmin can access superadmin area
    Route::middleware('role:superadmin')->group(function () {
        Route::get('/superadmin/dashboard', [SuperadminDashboardController::class, 'index'])->name('superadmin.dashboard');
    });
});
