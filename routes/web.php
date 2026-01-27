<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ImportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BookController::class, 'index'])->name('list');

/*
 * Login
 */
Route::get('/login', [AuthController::class, 'showLogin'])->name('view.login');
Route::post('/login', [AuthController::class, 'login'])->name('login');

/*
 * Register
 */
Route::get('/register', [AuthController::class, 'showRegister'])->name('view.register');
Route::post('/register', [AuthController::class, 'register'])->name('register');

/*
 * Logout
 */
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/import', [ImportController::class, 'index'])->name('import.index');
Route::get('/history', [ImportController::class, 'history'])->name('import.history');
Route::post('/import', [ImportController::class, 'store'])->name('import.store');

Route::resource('books', BookController::class)
    ->except(['index', 'show'])
    ->middleware('auth');
