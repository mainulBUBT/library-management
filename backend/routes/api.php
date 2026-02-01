<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\LoanController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\FineController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function () {
    // Public endpoints
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    // Protected endpoints (Sanctum)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);

        // Catalog - browse resources (public for authenticated users)
        Route::get('/catalog', [CatalogController::class, 'index']);
        Route::get('/catalog/{id}', [CatalogController::class, 'show']);
        Route::get('/categories', [CategoryController::class, 'index']);
        Route::get('/categories/{id}', [CategoryController::class, 'show']);
        Route::get('/authors', [AuthorController::class, 'index']);
        Route::get('/authors/{id}', [AuthorController::class, 'show']);

        // Member profile
        Route::get('/profile', [ProfileController::class, 'show']);
        Route::put('/profile', [ProfileController::class, 'update']);
        Route::post('/change-password', [ProfileController::class, 'changePassword']);

        // Member activity
        Route::get('/my-loans', [LoanController::class, 'myLoans']);
        Route::post('/loans/{loan}/renew', [LoanController::class, 'renew']);
        Route::get('/my-reservations', [ReservationController::class, 'myReservations']);
        Route::post('/reservations', [ReservationController::class, 'store']);
        Route::delete('/reservations/{id}', [ReservationController::class, 'cancel']);
        Route::get('/my-fines', [FineController::class, 'myFines']);
    });
});
