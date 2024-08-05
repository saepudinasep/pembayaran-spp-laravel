<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\RegisterController;
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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
});
