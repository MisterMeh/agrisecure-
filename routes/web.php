<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\Auth\TwoFactorController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
//use App\Http\Controllers\UserManagement;

Route::get('/', function () {
    return view('auth.login');
})->name('login');

/* Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard'); */
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/requests-management', function () {    
        return view('admin/requests');
    })->name('admin.requests');

    Route::get('/user-management', function () {
        return view('admin/user-management');
    })->name('user_management');
    Route::get('/user-file-management', function () {
        return view('employee/file-management');
    })->name('employee-file');
    Route::get('/admin-file-management', function () {
        return view('admin/file-management');
    })->name('admin-file');
    Route::get('/audit-logs', function () {
        return view('admin/autditlogs');
    })->name('audit-logs');

});

Route::get('my-captcha/reload', function () {
    return response()->json(['captcha' => captcha_img()]);
})->name('my-captcha.reload');


Route::prefix('2fa')->middleware('web')->group(function () {
    Route::get('verify', [TwoFactorController::class, 'index'])->name('2fa.index');
    Route::post('verify', [TwoFactorController::class, 'store'])->name('2fa.store');
    Route::get('resend', [TwoFactorController::class, 'resend'])->name('2fa.resend');
});

require __DIR__.'/auth.php';
