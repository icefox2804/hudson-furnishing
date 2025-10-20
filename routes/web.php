<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\MaterialController as AdminMaterialController;
use App\Http\Controllers\Admin\OfferController as AdminOfferController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\VisitorStatsController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\Admin\HomeProductSectionController;
use App\Http\Controllers\Admin\TrashController;
use App\Http\Controllers\Admin\LiveChatController;
use App\Http\Controllers\User\UserController as UserUserController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\Admin\ChatController;


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

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');

// Products Routes
Route::get('/products/{section}', [ProductController::class, 'bySection'])->name('products.section')->where('section', '[a-zA-Z0-9\-_]+');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/product/{product}', [ProductController::class, 'show'])->name('product.show');

// Categories Routes
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

// Brands Routes
Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');

// Materials Routes
Route::get('/materials', [MaterialController::class, 'index'])->name('materials.index');

// Offers Routes
Route::get('/offers', [OfferController::class, 'index'])->name('offers.index');

// Gallery Routes
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');

// Contact Routes
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->middleware('auth')->name('contact.store');

// Review Routes
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

// Visitor Stats
Route::post('/track-visitor', [VisitorController::class, 'track'])->name('visitor.track');

// User Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [UserUserController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/profile', [UserUserController::class, 'profile'])->name('user.profile');
    Route::post('/profile/update', [UserUserController::class, 'updateProfile'])->name('user.updateProfile');
    Route::post('/profile/password', [UserUserController::class, 'updatePassword'])->name('user.updatePassword');
    Route::get('/favorites', [UserUserController::class, 'favorites'])->name('user.favorites');    
    Route::post('/favorites/toggle/{product:slug}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registration Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

// Profile Routes (Protected)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile/change-password', [ProfileController::class, 'editPassword'])->name('password.edit');
    Route::post('/profile/change-password', [ProfileController::class, 'updatePassword'])->name('password.update');
});

// Password Reset Routes
Route::middleware('guest')->prefix('password')->name('password.')->group(function () {

    // Form gửi email quên mật khẩu
    Route::get('/forgot', [ForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('request'); // route name: password.request
        // URL: /password/forgot

    // Gửi email reset mật khẩu
    Route::post('/forgot', [ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('email'); // route name: password.email
        // URL: /password/forgot

    // Form reset mật khẩu (link từ email)
    Route::get('/reset/{token}', [ResetPasswordController::class, 'showResetForm'])
        ->name('reset'); // route name: password.reset
        // URL: /password/reset/{token}

    // Submit reset mật khẩu
    Route::post('/reset', [ResetPasswordController::class, 'reset'])
        ->name('update'); // route name: password.update
        // URL: /password/reset

});

// Admin Routes (Protected)
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Products Management
    Route::resource('products', AdminProductController::class)->names([
        'index'   => 'admin.products.index',
        'create'  => 'admin.products.create',
        'store'   => 'admin.products.store',
        'show'    => 'admin.products.show',
        'edit'    => 'admin.products.edit',
        'update'  => 'admin.products.update',
        'destroy' => 'admin.products.destroy'
        
    ]);

    Route::post('products/bulk-delete', [AdminProductController::class, 'bulkDelete'])
        ->name('admin.products.bulk-delete');
    Route::post('products/{product}/images', [AdminProductController::class, 'uploadImages'])->name('admin.products.images');
    Route::delete('products/{product}/images/{image}', [AdminProductController::class, 'deleteImage'])->name('admin.products.images.destroy');
    
    // Categories Management
    Route::resource('categories', AdminCategoryController::class)->names([
        'index' => 'admin.categories.index',
        'create' => 'admin.categories.create',
        'store' => 'admin.categories.store',
        'show' => 'admin.categories.show',
        'edit' => 'admin.categories.edit',
        'update' => 'admin.categories.update',
        'destroy' => 'admin.categories.destroy'
    ]);
    
    // Brands Management
    Route::resource('brands', AdminBrandController::class)->names([
        'index' => 'admin.brands.index',
        'create' => 'admin.brands.create',
        'store' => 'admin.brands.store',
        'show' => 'admin.brands.show',
        'edit' => 'admin.brands.edit',
        'update' => 'admin.brands.update',
        'destroy' => 'admin.brands.destroy'
    ]);
    
    // Materials Management
    Route::resource('materials', AdminMaterialController::class)->names([
        'index' => 'admin.materials.index',
        'create' => 'admin.materials.create',
        'store' => 'admin.materials.store',
        'show' => 'admin.materials.show',
        'edit' => 'admin.materials.edit',
        'update' => 'admin.materials.update',
        'destroy' => 'admin.materials.destroy'
    ]);
    
    // Offers Management
    Route::resource('offers', AdminOfferController::class)->names([
        'index' => 'admin.offers.index',
        'create' => 'admin.offers.create',
        'store' => 'admin.offers.store',
        'show' => 'admin.offers.show',
        'edit' => 'admin.offers.edit',
        'update' => 'admin.offers.update',
        'destroy' => 'admin.offers.destroy'
    ]);
    
    // Reviews Management
    Route::get('reviews', [AdminReviewController::class, 'index'])->name('admin.reviews.index');
    Route::patch('reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('admin.reviews.approve');
    Route::delete('reviews/{review}', [AdminReviewController::class, 'destroy'])->name('admin.reviews.destroy');
    
    // Gallery Management
    Route::get('gallery', [AdminGalleryController::class, 'index'])->name('admin.gallery.index');
    Route::post('gallery', [AdminGalleryController::class, 'store'])->name('admin.gallery.store');
    Route::delete('gallery/{image}', [AdminGalleryController::class, 'destroy'])->name('admin.gallery.destroy');
    Route::patch('gallery/{image}/primary', [AdminGalleryController::class, 'setPrimary'])->name('admin.gallery.primary');
    
    // Contacts Management
    Route::get('contacts', [AdminContactController::class, 'index'])->name('admin.contacts.index');
    Route::get('contacts/{contact}', [AdminContactController::class, 'show'])->name('admin.contacts.show');
    Route::put('contacts/{contact}', [AdminContactController::class, 'update'])->name('admin.contacts.update');
    Route::patch('contacts/{contact}/read', [AdminContactController::class, 'markAsRead'])->name('admin.contacts.read');
    Route::patch('contacts/{contact}/replied', [AdminContactController::class, 'markAsReplied'])->name('admin.contacts.replied');
    Route::delete('contacts/{contact}', [AdminContactController::class, 'destroy'])->name('admin.contacts.destroy');
    
    //visitor 
    
    Route::get('visitors', [VisitorStatsController::class, 'index'])->name('admin.visitors.index');

    // Settings Management
    Route::get('settings', [SettingController::class, 'index'])->name('admin.settings.index');
    Route::put('settings', [SettingController::class, 'update'])->name('admin.settings.update');
    
    // Trash Management
    Route::prefix('trash')
        ->name('admin.trash.')
        ->group(function () {
            Route::get('/', [TrashController::class, 'index'])->name('index');
            Route::get('/{model}', [TrashController::class, 'show'])->name('show');
            Route::post('/restore', [TrashController::class, 'restore'])->name('restore');
            Route::post('/force-delete', [TrashController::class, 'forceDelete'])->name('force-delete');
            Route::post('/bulk-restore', [TrashController::class, 'bulkRestore'])->name('bulk-restore');
            Route::post('/bulk-force-delete', [TrashController::class, 'bulkForceDelete'])->name('bulk-force-delete');
            Route::post('/cleanup', [TrashController::class, 'cleanup'])->name('cleanup');
        });


    Route::resource('users', UserController::class) ->names([
        'index'   => 'admin.users.index',
        'create'  => 'admin.users.create',
        'store'   => 'admin.users.store',
        'show'    => 'admin.users.show',
        'edit'    => 'admin.users.edit',
        'update'  => 'admin.users.update',
        'destroy' => 'admin.users.destroy'
    ]);

    Route::resource('home-sections', HomeProductSectionController::class)->names([
        'index'   => 'admin.home-sections.index',
        'create'  => 'admin.home-sections.create',
        'store'   => 'admin.home-sections.store',
        'show'    => 'admin.home-sections.show',
        'edit'    => 'admin.home-sections.edit',
        'update'  => 'admin.home-sections.update',
        'destroy' => 'admin.home-sections.destroy'
    ]);

    Route::get('/chats', [ChatController::class, 'index'])->name('admin.chat.index');
    Route::get('/chats/{id}', [ChatController::class, 'show'])->name('admin.chat.show');
    Route::get('/chat/history/{sessionId}', [ChatController::class, 'history']);
});
