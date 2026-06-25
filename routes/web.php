<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/our-work', [HomeController::class, 'ourWork'])->name('our-work');
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
        Route::get('/case-studies', [AdminAuthController::class, 'caseStudies'])->name('case-studies.index');
        Route::get('/case-studies/create', [AdminAuthController::class, 'createCaseStudy'])->name('case-studies.create');
        Route::post('/case-studies', [AdminAuthController::class, 'storeCaseStudy'])->name('case-studies.store');
        Route::get('/case-studies/{caseStudyId}/edit', [AdminAuthController::class, 'editCaseStudy'])->name('case-studies.edit');
        Route::put('/case-studies/{caseStudyId}', [AdminAuthController::class, 'updateCaseStudy'])->name('case-studies.update');
        Route::delete('/case-studies/{caseStudyId}', [AdminAuthController::class, 'deleteCaseStudy'])->name('case-studies.delete');
        Route::get('/posts', [AdminAuthController::class, 'posts'])->name('posts.index');
        Route::get('/posts/create', [AdminAuthController::class, 'createPost'])->name('posts.create');
        Route::post('/posts', [AdminAuthController::class, 'storePost'])->name('posts.store');
        Route::get('/posts/{postId}/edit', [AdminAuthController::class, 'editPost'])->name('posts.edit');
        Route::put('/posts/{postId}', [AdminAuthController::class, 'updatePost'])->name('posts.update');
        Route::delete('/posts/{postId}', [AdminAuthController::class, 'deletePost'])->name('posts.delete');
        Route::post('/posts/bulk-delete', [AdminAuthController::class, 'bulkDeletePosts'])->name('posts.bulk-delete');
        Route::get('/pages/create', [AdminAuthController::class, 'createPage'])->name('pages.create');
        Route::post('/pages', [AdminAuthController::class, 'storePage'])->name('pages.store');
        Route::get('/pages/{pageId}/edit', [AdminAuthController::class, 'editPage'])->name('pages.edit');
        Route::put('/pages/{pageId}', [AdminAuthController::class, 'updatePage'])->name('pages.update');
        Route::delete('/pages/{pageId}', [AdminAuthController::class, 'deletePage'])->name('pages.delete');
        Route::post('/pages/bulk-delete', [AdminAuthController::class, 'bulkDeletePages'])->name('pages.bulk-delete');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    });
});

Route::get('/{page}', [HomeController::class, 'page'])->name('pages.show');
