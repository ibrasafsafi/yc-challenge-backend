<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
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


Route::name('api.')
  ->prefix('v1')
  ->middleware('web')
  ->group(function () {

    // Public routes
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register'])->name('register');

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
      Route::get('user', [AuthController::class, 'user'])->name('user');
      Route::post('logout', [AuthController::class, 'logout'])->name('logout');

      Route::apiResource('products', ProductController::class);

    });
  });

