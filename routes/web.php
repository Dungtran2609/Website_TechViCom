<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsAdmin;

// Trang chủ client
Route::get('/', function () {
    return view('client.home');
})->name('home');

// Trang dashboard admin (chỉ cho admin hoặc staff)
Route::get('/admin', function () {
    return view('admin.dashboard');
})->middleware(['auth', IsAdmin::class])->name('admin.dashboard');

require __DIR__.'/auth.php';
