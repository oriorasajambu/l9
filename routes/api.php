<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LogController;
use App\Http\Controllers\Api\TodoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//API route for register new user
Route::post('/register', [AuthController::class, 'register']);
//API route for login
Route::post('/login', [AuthController::class, 'login']);

Route::controller(AuthController::class)->group(function () {
    Route::get('/unauthorized', 'unauthorized');
    Route::post('/unauthorized', 'unauthorized');
    Route::put('/unauthorized', 'unauthorized');
});

//Protecting Routes
Route::group(['middleware' => ['auth:api']], function () {
    Route::resource('/todos', TodoController::class);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/profile/delete', [AuthController::class, 'deleteAccount']);
    // API route for logging
    Route::get('/logs', [LogController::class, 'index']);
    Route::post('/logs', [LogController::class, 'find']);
    // API route for logout user
    Route::post('/logout', [AuthController::class, 'logout']);
});
