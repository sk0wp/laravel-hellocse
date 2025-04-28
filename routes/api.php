<?php

use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ProfileController;
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

Route::get('/profiles', [ProfileController::class, 'list']);

Route::middleware('auth:api')->group(function () {
    Route::post('/comment/create', [CommentController::class, 'create']);

    Route::prefix('/profile')->group(function () {
        Route::post('/create', [ProfileController::class, 'create']);
        Route::post('/edit', [ProfileController::class, 'edit']);
        Route::delete('/edit', [ProfileController::class, 'delete']);
    });
});
