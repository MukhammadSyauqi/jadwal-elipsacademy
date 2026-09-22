<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminJadwalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperadminCabangController;
use App\Http\Controllers\SuperadminDashboardController;
use App\Http\Controllers\SuperadminJadwalController;
use App\Http\Controllers\SuperadminProgramController;
use App\Http\Controllers\SuperadminTentorController;
use App\Http\Controllers\SuperadminUserController;
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
        Route::get('/superadmin/jadwal', [SuperadminJadwalController::class, 'index'])->name('superadmin.jadwal.index');

        // Master Data: Cabang
        Route::get('/superadmin/cabang', [SuperadminCabangController::class, 'index'])->name('superadmin.cabang.index');
        Route::post('/superadmin/cabang', [SuperadminCabangController::class, 'store'])->name('superadmin.cabang.store');
        Route::put('/superadmin/cabang/{cabang}', [SuperadminCabangController::class, 'update'])->name('superadmin.cabang.update');
        Route::post('/superadmin/cabang/{cabang}/toggle-status', [SuperadminCabangController::class, 'toggleStatus'])->name('superadmin.cabang.toggle-status');
        Route::delete('/superadmin/cabang/{cabang}', [SuperadminCabangController::class, 'destroy'])->name('superadmin.cabang.destroy');

        // Master Data: Program Kursus
        Route::get('/superadmin/program', [SuperadminProgramController::class, 'index'])->name('superadmin.program.index');
        Route::post('/superadmin/program', [SuperadminProgramController::class, 'store'])->name('superadmin.program.store');
        Route::put('/superadmin/program/{program}', [SuperadminProgramController::class, 'update'])->name('superadmin.program.update');
        Route::post('/superadmin/program/{program}/toggle-status', [SuperadminProgramController::class, 'toggleStatus'])->name('superadmin.program.toggle-status');
        Route::delete('/superadmin/program/{program}', [SuperadminProgramController::class, 'destroy'])->name('superadmin.program.destroy');

        // Master Data: Tentor
        Route::get('/superadmin/tentor', [SuperadminTentorController::class, 'index'])->name('superadmin.tentor.index');
        Route::post('/superadmin/tentor', [SuperadminTentorController::class, 'store'])->name('superadmin.tentor.store');
        Route::put('/superadmin/tentor/{tentor}', [SuperadminTentorController::class, 'update'])->name('superadmin.tentor.update');
        Route::post('/superadmin/tentor/{tentor}/toggle-status', [SuperadminTentorController::class, 'toggleStatus'])->name('superadmin.tentor.toggle-status');
        Route::delete('/superadmin/tentor/{tentor}', [SuperadminTentorController::class, 'destroy'])->name('superadmin.tentor.destroy');

        // Manajemen Akun Pengguna
        Route::get('/superadmin/user', [SuperadminUserController::class, 'index'])->name('superadmin.user.index');
        Route::post('/superadmin/user', [SuperadminUserController::class, 'store'])->name('superadmin.user.store');
        Route::put('/superadmin/user/{user}', [SuperadminUserController::class, 'update'])->name('superadmin.user.update');
        Route::post('/superadmin/user/{user}/reset-password', [SuperadminUserController::class, 'resetPassword'])->name('superadmin.user.reset-password');
        Route::delete('/superadmin/user/{user}', [SuperadminUserController::class, 'destroy'])->name('superadmin.user.destroy');
    });
});
