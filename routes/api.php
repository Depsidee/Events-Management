<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\userController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();


});
   Route::prefix('Admin')->group(function () {

    Route::post('login/Admin', [App\Http\Controllers\AuthController::class, 'AdminLogin']);

    Route::middleware('auth:api')->group(function () {

   Route::get('index', [App\Http\Controllers\userController::class, 'index']);
   Route::get('showPersonalInfo/{id}', [App\Http\Controllers\userController::class, 'showPersonalInfo']);
   Route::get('logout', [App\Http\Controllers\AuthController::class, 'logout']);
   Route::post('update/Admin/{id}', [App\Http\Controllers\userController::class, 'updatePesonalInfo_Admin']);

    });
   });

Route::prefix('admin_hall')->group(function () {

    Route::post('register', [App\Http\Controllers\AuthController::class, 'Register']);
    Route::post('user/Login', [App\Http\Controllers\AuthController::class, 'userLogin']);

    Route::middleware('auth:api')->group(function () {

    Route::get('logout', [App\Http\Controllers\AuthController::class, 'logout']);

    Route::get('showPersonalInfo/{id}', [App\Http\Controllers\userController::class, 'showPersonalInfo']);
    Route::post('update/user/{id}', [App\Http\Controllers\userController::class, 'updatePesonalInfo']);

});});

Route::prefix('User')->group(function () {

Route::post('register', [App\Http\Controllers\AuthController::class, 'Register']);
Route::post('user/Login', [App\Http\Controllers\AuthController::class, 'userLogin']);

Route::middleware('auth:api')->group(function () {

Route::get('logout', [App\Http\Controllers\AuthController::class, 'logout']);
Route::get('showPersonalInfo/{id}', [App\Http\Controllers\userController::class, 'showPersonalInfo']);
Route::post('update/user/{id}', [App\Http\Controllers\userController::class, 'updatePesonalInfo']);

});});