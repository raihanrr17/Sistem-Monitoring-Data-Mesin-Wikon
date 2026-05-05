<?php

use Illuminate\Support\Facades\Route;
use App\Models\Machine;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\MachineController;

// ─── AUTH ───────────────────────────────────────────────────────────────────

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ─── USER (login wajib, role: user atau admin) ────────────────────────────

Route::middleware(['auth', 'role:user,admin'])->group(function () {
    Route::get('/home', function () {
        return view('home');
    })->name('home');

    Route::get('/reports', function () {
        return view('reports', [
            'machines' => Machine::all(),
        ]);
    })->name('reports');
});

// ─── ADMIN ONLY ───────────────────────────────────────────────────────────

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', function () {
        return view('admin');
    })->name('admin.dashboard');

    Route::get('/admin-input', function () {
        return view('admin-input');
    })->name('admin.input');

    Route::get('/admin-reports', function () {
        return view('admin-reports');
    })->name('admin.reports');
});

// ─── API ──────────────────────────────────────────────────────────────────
// API dashboard & mesin tetap bisa diakses oleh user dan admin yang sudah login.
// Jika nanti perlu public, hapus middleware auth dari group ini.
Route::middleware(['auth'])->prefix('api')->group(function () {
    Route::get('/dashboard', [MachineController::class, 'dashboard']);
    Route::get('/machines',  [MachineController::class, 'index']);
    Route::post('/machines/store',   [MachineController::class, 'store']);
    Route::put('/machines/{id}',     [MachineController::class, 'update']);
    Route::delete('/machines/{id}',  [MachineController::class, 'destroy']);
});
