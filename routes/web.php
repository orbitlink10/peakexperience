<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/contact', [HomeController::class, 'submitContact'])->name('contact.submit');
Route::get('/services/{service}', [HomeController::class, 'service'])->name('services.show');
Route::get('/homepage-assets/{path}', [HomeController::class, 'asset'])
    ->where('path', '.*')
    ->name('homepage.asset');

Route::get('/admin', function () {
    return redirect()->route(session('admin_authenticated') ? 'admin.gallery' : 'admin.login');
});

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::middleware('admin.guest')->group(function (): void {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    });

    Route::middleware('admin.auth')->group(function (): void {
        Route::get('/dashboard', function () {
            return redirect()->route('admin.gallery');
        })->name('dashboard');
        Route::get('/section/{section}', [AdminAuthController::class, 'section'])->name('section');
        Route::get('/gallery', [AdminAuthController::class, 'gallery'])->name('gallery');
        Route::post('/gallery/upload', [AdminAuthController::class, 'uploadGalleryImage'])->name('gallery.upload');
        Route::post('/gallery/delete', [AdminAuthController::class, 'deleteGalleryImages'])->name('gallery.delete');
        Route::get('/homepage', [AdminAuthController::class, 'showHomepage'])->name('homepage');
        Route::post('/homepage', [AdminAuthController::class, 'updateHomepage'])->name('homepage.update');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    });
});
