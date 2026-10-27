<?php

use App\Http\Controllers\Customer\DashboardController as CustomerDashboard;
use App\Http\Controllers\Customer\ItemController      as CustomerItem;
use App\Http\Controllers\Customer\RequestController   as CustomerRequest;
use App\Http\Controllers\Supplier\DashboardController as SupplierDashboard;
use App\Http\Controllers\Supplier\ItemController      as SupplierItem;
use App\Http\Controllers\Supplier\RequestController   as SupplierRequest;
use App\Http\Controllers\Customer\FavoriteController as CustomerFavorite;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/* Public */
Route::get('/', function () {
    // Kalau sudah login → langsung ke dashboard sesuai role
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('landing');
})->name('home');

Route::view('/tentang', 'tentang')->name('tentang');

/* Onboarding */
Route::middleware('auth')->group(function () {
    Route::get('/onboarding/role',  [OnboardingController::class, 'show'])->name('onboarding.role');
    Route::post('/onboarding/role', [OnboardingController::class, 'store'])->name('onboarding.role.store');
    Route::get('/verify-otp', [\App\Http\Controllers\Auth\OtpVerificationController::class, 'show'])->name('verification.otp');
    Route::post('/verify-otp', [\App\Http\Controllers\Auth\OtpVerificationController::class, 'verify'])->name('verification.otp.verify');
    Route::post('/verify-otp/resend', [\App\Http\Controllers\Auth\OtpVerificationController::class, 'resend'])->name('verification.otp.resend');
});

/* Dashboard router */
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    $u = auth()->user();
    if (! $u->hasSelectedRole()) return redirect()->route('onboarding.role');
    return $u->isSupplier()
        ? redirect()->route('supplier.dashboard')
        : redirect()->route('customer.dashboard');
})->name('dashboard');

/* ============ CUSTOMER ============ */
Route::middleware(['auth', 'verified', 'role:customer'])
    ->prefix('customer')->name('customer.')
    ->group(function () {
        Route::get('/dashboard',    [CustomerDashboard::class, 'index'])->name('dashboard');
        Route::get('/search',       [CustomerDashboard::class, 'search'])->name('search');
        Route::get('/items/{item}', [CustomerItem::class, 'show'])->name('items.show');
        Route::post('/items/{item}/request', [CustomerRequest::class, 'store'])->name('items.request');
        Route::get('/requests',              [CustomerRequest::class, 'index'])->name('requests.index');
        Route::patch('/requests/{request}/cancel', [CustomerRequest::class, 'cancel'])->name('requests.cancel');
        Route::get('/favorites', [CustomerFavorite::class, 'index'])->name('favorites');
        Route::post('/items/{item}/favorite', [CustomerFavorite::class, 'toggle'])->name('items.favorite');
        Route::post('/items/{item}/unfavorite', [\App\Http\Controllers\Customer\FavoriteController::class, 'unfavorite'])->name('items.unfavorite');

        Route::get('/wishlist',                        [\App\Http\Controllers\Customer\DemandListController::class, 'index'])->name('wishlist.index');
        Route::get('/wishlist/create',                 [\App\Http\Controllers\Customer\DemandListController::class, 'create'])->name('wishlist.create');
        Route::post('/wishlist',                       [\App\Http\Controllers\Customer\DemandListController::class, 'store'])->name('wishlist.store');
        Route::get('/wishlist/{wishlist}',             [\App\Http\Controllers\Customer\DemandListController::class, 'show'])->name('wishlist.show');
        Route::delete('/wishlist/{wishlist}',          [\App\Http\Controllers\Customer\DemandListController::class, 'destroy'])->name('wishlist.destroy');
        Route::post('/wishlist/{wishlist}/confirm',    [\App\Http\Controllers\Customer\DemandListController::class, 'confirm'])->name('wishlist.confirm');
        Route::post('/allocations/{allocation}/toggle',[\App\Http\Controllers\Customer\DemandListController::class, 'toggleAllocation'])->name('wishlist.toggle');
        Route::patch('/requests/{request}/complete', [\App\Http\Controllers\Customer\RequestController::class, 'complete'])->name('requests.complete');
    });

/* ============ SUPPLIER ============ */
Route::middleware(['auth', 'verified', 'role:supplier'])
    ->prefix('supplier')->name('supplier.')
    ->group(function () {
        Route::get('/dashboard', [SupplierDashboard::class, 'index'])->name('dashboard');
        Route::resource('items', SupplierItem::class);
        Route::get('/requests',                          [SupplierRequest::class, 'index'])->name('requests.index');
        Route::patch('/requests/{itemRequest}/accept',   [SupplierRequest::class, 'accept'])->name('requests.accept');
        Route::patch('/requests/{itemRequest}/reject',   [SupplierRequest::class, 'reject'])->name('requests.reject');
        Route::patch('/requests/{itemRequest}/complete', [SupplierRequest::class, 'complete'])->name('requests.complete');
    });

/* Profile */
Route::middleware('auth')->group(function () {
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/profile/avatar',   [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'destroyAvatar'])->name('profile.avatar.destroy');
});


require __DIR__.'/auth.php';
