<?php

use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FineController;
use App\Http\Controllers\Admin\LoanController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\ResourceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Something great!
|
*/

// Home page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Authentication Routes
Route::prefix('admin/auth')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showAdminLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'adminLogin'])->name('login.submit');
});

// Admin Routes (Protected)
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified'])
    ->group(function () {

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Resources
        Route::prefix('resources')->name('resources.')->group(function () {
            Route::get('/', [ResourceController::class, 'index'])->name('index');
            Route::get('/create', [ResourceController::class, 'create'])->name('create');
            Route::post('/', [ResourceController::class, 'store'])->name('store');
            Route::get('/{resource}', [ResourceController::class, 'show'])->name('show');
            Route::get('/{resource}/edit', [ResourceController::class, 'edit'])->name('edit');
            Route::put('/{resource}', [ResourceController::class, 'update'])->name('update');
            Route::delete('/{resource}', [ResourceController::class, 'destroy'])->name('destroy');
        });

        // Categories
        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('index');
            Route::get('/create', [CategoryController::class, 'create'])->name('create');
            Route::post('/', [CategoryController::class, 'store'])->name('store');
            Route::get('/{category}', [CategoryController::class, 'show'])->name('show');
            Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
            Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
            Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
        });

        // Authors
        Route::prefix('authors')->name('authors.')->group(function () {
            Route::get('/', [AuthorController::class, 'index'])->name('index');
            Route::get('/create', [AuthorController::class, 'create'])->name('create');
            Route::post('/', [AuthorController::class, 'store'])->name('store');
            Route::get('/{author}', [AuthorController::class, 'show'])->name('show');
            Route::get('/{author}/edit', [AuthorController::class, 'edit'])->name('edit');
            Route::put('/{author}', [AuthorController::class, 'update'])->name('update');
            Route::delete('/{author}', [AuthorController::class, 'destroy'])->name('destroy');
        });

        // Members
        Route::prefix('members')->name('members.')->group(function () {
            Route::get('/', [MemberController::class, 'index'])->name('index');
            Route::get('/create', [MemberController::class, 'create'])->name('create');
            Route::post('/', [MemberController::class, 'store'])->name('store');
            Route::get('/{member}', [MemberController::class, 'show'])->name('show');
            Route::get('/{member}/edit', [MemberController::class, 'edit'])->name('edit');
            Route::put('/{member}', [MemberController::class, 'update'])->name('update');
            Route::delete('/{member}', [MemberController::class, 'destroy'])->name('destroy');
        });

        // Loans
        Route::prefix('loans')->name('loans.')->group(function () {
            Route::get('/', [LoanController::class, 'index'])->name('index');
            Route::get('/create', [LoanController::class, 'create'])->name('create');
            Route::post('/', [LoanController::class, 'store'])->name('store');
            Route::get('/{loan}', [LoanController::class, 'show'])->name('show');
            Route::post('/{loan}/return', [LoanController::class, 'return'])->name('return');
            Route::post('/{loan}/renew', [LoanController::class, 'renew'])->name('renew');
        });

        // Reservations
        Route::prefix('reservations')->name('reservations.')->group(function () {
            Route::get('/', [ReservationController::class, 'index'])->name('index');
            Route::get('/create', [ReservationController::class, 'create'])->name('create');
            Route::post('/', [ReservationController::class, 'store'])->name('store');
            Route::get('/{reservation}', [ReservationController::class, 'show'])->name('show');
            Route::delete('/{reservation}', [ReservationController::class, 'destroy'])->name('destroy');
            Route::post('/{reservation}/mark-ready', [ReservationController::class, 'markReady'])->name('mark-ready');
            Route::post('/{reservation}/fulfill', [ReservationController::class, 'fulfill'])->name('fulfill');
        });

        // Fines
        Route::prefix('fines')->name('fines.')->group(function () {
            Route::get('/', [FineController::class, 'index'])->name('index');
            Route::get('/create', [FineController::class, 'create'])->name('create');
            Route::post('/', [FineController::class, 'store'])->name('store');
            Route::get('/{fine}', [FineController::class, 'show'])->name('show');
            Route::post('/{fine}/waive', [FineController::class, 'waive'])->name('waive');
            Route::post('/{fine}/payments', [PaymentController::class, 'store'])->name('payment');
        });

        // Payments
        Route::prefix('payments')->name('payments.')->group(function () {
            Route::get('/', [PaymentController::class, 'index'])->name('index');
        });

        // Settings
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [SettingController::class, 'index'])->name('index');
            Route::put('/', [SettingController::class, 'update'])->name('update');
            Route::post('/initialize', [SettingController::class, 'initialize'])->name('initialize');
        });
    });
