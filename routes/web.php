<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SppController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StudentController;
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

Route::get('/', [HomeController::class, 'index']);
Route::get('/login', [LoginController::class, 'index']);
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::middleware(['check.user.exists'])->group(function () {
    Route::get('/register/index', [RegisterController::class, 'register'])->name('register.index');
    Route::post('/register/process', [RegisterController::class, 'process'])->name('register.process');
});

Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/password/reset', [PasswordController::class, 'reset'])->name('password.reset');
Route::post('/password/process', [PasswordController::class, 'process'])->name('password.process');

// Route::middleware(['guest'])->group(function () {
// });

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Data Master SPP
    Route::get('/spp', [SppController::class, 'index'])->name('spp.index');
    Route::post('/spp/add', [SppController::class, 'store'])->name('spp.add');
    Route::put('/spp/update/{id}', [SppController::class, 'update'])->name('spp.update');
    Route::delete('/spp/destroy/{id}', [SppController::class, 'destroy'])->name('spp.destroy');
    Route::get('/spp/export', [SppController::class, 'export'])->name('spp.export');

    // Data Master Admin
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

    // Data Master Staff
    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');

    // Data Master Student
    Route::get('/student', [StudentController::class, 'index'])->name('student.index');
});
