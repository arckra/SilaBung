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
});

/* Dashboard router */
Route::middleware('auth')->get('/dashboard', function () {
    $u = auth()->user();
    if (! $u->hasSelectedRole()) return redirect()->route('onboarding.role');
    return $u->isSupplier()
        ? redirect()->route('supplier.dashboard')
        : redirect()->route('customer.dashboard');
})->name('dashboard');

/* ============ CUSTOMER ============ */
Route::middleware(['auth', 'role:customer'])
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

        Route::get('/wishlist',                        [\App\Http\Controllers\Customer\DemandListController::class, 'index'])->name('wishlist.index');
        Route::get('/wishlist/create',                 [\App\Http\Controllers\Customer\DemandListController::class, 'create'])->name('wishlist.create');
        Route::post('/wishlist',                       [\App\Http\Controllers\Customer\DemandListController::class, 'store'])->name('wishlist.store');
        Route::get('/wishlist/{wishlist}',             [\App\Http\Controllers\Customer\DemandListController::class, 'show'])->name('wishlist.show');
        Route::delete('/wishlist/{wishlist}',          [\App\Http\Controllers\Customer\DemandListController::class, 'destroy'])->name('wishlist.destroy');
        Route::post('/wishlist/{wishlist}/confirm',    [\App\Http\Controllers\Customer\DemandListController::class, 'confirm'])->name('wishlist.confirm');
        Route::post('/allocations/{allocation}/toggle',[\App\Http\Controllers\Customer\DemandListController::class, 'toggleAllocation'])->name('wishlist.toggle');
    });

/* ============ SUPPLIER ============ */
Route::middleware(['auth', 'role:supplier'])
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

Route::middleware(['auth'])
    ->prefix('chat')
    ->name('chat.')
    ->group(function () {
        Route::get('/group/{group}',          [\App\Http\Controllers\ChatController::class, 'groupPage'])->name('group');
        Route::get('/group/{group}/messages', [\App\Http\Controllers\ChatController::class, 'groupMessages'])->name('group.messages');
        Route::post('/group/{group}/send',    [\App\Http\Controllers\ChatController::class, 'groupSend'])->name('group.send');

        Route::get('/',                       [\App\Http\Controllers\ChatController::class, 'page'])->name('index');
        Route::get('/list',                   [\App\Http\Controllers\ChatController::class, 'list'])->name('list');
        Route::get('/{conversation}/messages',[\App\Http\Controllers\ChatController::class, 'messages'])->name('messages');
        Route::post('/{conversation}/send',   [\App\Http\Controllers\ChatController::class, 'send'])->name('send');
        Route::post('/start',                 [\App\Http\Controllers\ChatController::class, 'start'])->name('start');
    });