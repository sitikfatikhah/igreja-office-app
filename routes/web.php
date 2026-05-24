<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\EnrollFaceController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get ('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.list');
    Route::get('/attendances/create', [AttendanceController::class, 'create'])->name('attendances.create');
    Route::get('/attendances/{id}/edit', [AttendanceController::class, 'edit'])->name('attendances.edit');
    Route::get('/attendances/list', [AttendanceController::class, 'index'])->name('attendances.list');
    Route::get('/attendances/{id}/view', [AttendanceController::class, 'show'])->name('attendances.show');

    Route::get('/admin/enroll-face', [EnrollFaceController::class, 'index'])->name('enroll-face.index');
    Route::get('/admin/enroll-face/save-face', [EnrollFaceController::class, 'create'])->name('enroll-face.create');
    Route::get('/admin/enroll-face/{id}/edit', [EnrollFaceController::class, 'edit'])->name('enroll-face.edit');
    Route::post('/attendances', [AttendanceController::class, 'store'])->name('attendances.store');
    Route::post('/admin/enroll-face/save-face', [EnrollFaceController::class, 'store'])->name('enroll-face.save');
    
    Route::get('/create', [UserController::class, 'create'])->name('users.create');
    Route::get('{record}/edit', [UserController::class, 'edit'])->name('users.edit');
});

