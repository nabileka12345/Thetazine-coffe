<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Home page (Landing page untuk semua user)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication routes
Route::get('/login', [DashboardController::class, 'login'])->name('login');
Route::get('/register', [DashboardController::class, 'register'])->name('register');

// Dashboard route (untuk admin saja)
// Note: Frontend akan handle redirect berdasarkan role
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
