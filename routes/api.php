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
Route::prefix('super_admin')->group(function () {

    Route::post('login', [App\Http\Controllers\AuthController::class, 'Login']);

    Route::middleware('auth:api')->group(function () {
        //auth
        Route::get('index', [App\Http\Controllers\userController::class, 'index']);
        Route::get('showPersonalInfo/{id}', [App\Http\Controllers\userController::class, 'showPersonalInfo']);
        Route::get('logout', [App\Http\Controllers\AuthController::class, 'logout']);
        Route::post('update/Admin/{id}', [App\Http\Controllers\userController::class, 'updatePesonalInfo_Admin']);
        //wallet
        Route::get('showWalllet', [App\Http\Controllers\PaidController::class, 'show_Wallet']);
        //song
        Route::post('create/song', [App\Http\Controllers\songController::class, 'create']);
        Route::post('update/song/{id}', [App\Http\Controllers\songController::class, 'updateSong']);
        Route::get('show/song', [App\Http\Controllers\songController::class, 'index']);
        Route::get('delete/song/{id}', [App\Http\Controllers\songController::class, 'deleteSong']);
        ///hall
        Route::get('index_hall', [App\Http\Controllers\HallController::class, 'index']);
        Route::get('acceptHall/{id}', [App\Http\Controllers\HallController::class, 'acceptHall']);
        Route::get('unrecordedHalls', [App\Http\Controllers\HallController::class, 'unrecordedHalls']);
        Route::get('hallDetails/{id}', [App\Http\Controllers\HallController::class, 'hallDetails']);
        Route::get('hallViews/{id}', [App\Http\Controllers\HallController::class, 'hallViews']);
        Route::get('rejectHall/{id}', [App\Http\Controllers\HallController::class, 'rejectHall']);
        Route::get('indexTypes', [App\Http\Controllers\HallTypeController::class, 'index']);
        Route::get('typeHalls/{id}', [App\Http\Controllers\HallTypeController::class, 'hallsOfType']);

        //reservation
        Route::get('indexReservations', [App\Http\Controllers\ReservationController::class, 'index']);
        Route::post('acceptReservation/{id}', [App\Http\Controllers\ReservationController::class, 'acceptReservation']);
        Route::get('rejectReservation/{id}', [App\Http\Controllers\ReservationController::class, 'rejectReservation']);
        Route::get('pendingReservations', [App\Http\Controllers\ReservationController::class, 'pendingReservations']);
        Route::get('allPreviousReservations', [App\Http\Controllers\ReservationController::class, 'allPreviousReservations']);
        Route::get('allUpcomingReservations', [App\Http\Controllers\ReservationController::class, 'allUpcomingReservations']);


    });
});


Route::prefix('admin_hall')->group(function () {

    Route::post('register', [App\Http\Controllers\AuthController::class, 'Register_adminHall']);
    Route::post('Login', [App\Http\Controllers\AuthController::class, 'Login']);

    Route::middleware('auth:api')->group(function () {

        Route::get('logout', [App\Http\Controllers\AuthController::class, 'logout']);

        Route::get('showPersonalInfo/{id}', [App\Http\Controllers\userController::class, 'showPersonalInfo']);
        Route::post('update/user/{id}', [App\Http\Controllers\userController::class, 'updatePesonalInfo']);
        Route::get('showWalllet', [App\Http\Controllers\PaidController::class, 'show_Wallet']);

        //hall
        Route::get('hallViews/{id}', [App\Http\Controllers\HallController::class, 'hallViews']);
        Route::post('update', [App\Http\Controllers\HallController::class, 'update']);
        //reservation
        Route::get('hallReservations', [App\Http\Controllers\ReservationController::class, 'hallReservations']);
        Route::get('hallPreviousReservations', [App\Http\Controllers\ReservationController::class, 'hallPreviousReservations']);
        Route::get('hallUpcomingReservations', [App\Http\Controllers\ReservationController::class, 'hallUpcomingReservations']);

    });
});


Route::prefix('client')->group(function () {

    Route::post('register', [App\Http\Controllers\AuthController::class, 'Register']);
    Route::post('Login', [App\Http\Controllers\AuthController::class, 'Login']);
    Route::get('login/google', [App\Http\Controllers\GoogleController::class, 'redirectToGoogle']);
    Route::get('login/google/callback', [App\Http\Controllers\GoogleController::class, 'handleGoogleCallback']);


    Route::middleware('auth:api')->group(function () {

        Route::get('logout', [App\Http\Controllers\AuthController::class, 'logout']);
        Route::get('showPersonalInfo/{id}', [App\Http\Controllers\userController::class, 'showPersonalInfo']);
        Route::post('update/user/{id}', [App\Http\Controllers\userController::class, 'updatePesonalInfo']);
        ///paid
        Route::get('paid/{id}', [App\Http\Controllers\PaidController::class, 'paid']);
        ///wallet
        Route::get('showWalllet/{id}', [App\Http\Controllers\PaidController::class, 'show_Wallet']);
        Route::post('create/wallet', [App\Http\Controllers\PaidController::class, 'createWallet']);
        Route::post('update/wallet/{id}', [App\Http\Controllers\PaidController::class, 'updateWalletBalancee']);

        //hall
        Route::get('index_hall', [App\Http\Controllers\HallController::class, 'index']);
        Route::post('hallFromCoordinates', [App\Http\Controllers\HallController::class, 'hallFromCoordinates']);
        Route::get('showAccordingRating', [App\Http\Controllers\HallController::class, 'showAccordingRating']);
        Route::get('lowestPrice', [App\Http\Controllers\HallController::class, 'lowestPrice']);
        Route::get('highestPrice', [App\Http\Controllers\HallController::class, 'highestPrice']);
        Route::get('smallestSpace', [App\Http\Controllers\HallController::class, 'smallestSpace']);
        Route::get('largestSpace', [App\Http\Controllers\HallController::class, 'largestSpace']);
        Route::get('hallDetails/{id}', [App\Http\Controllers\HallController::class, 'hallDetails']);
        Route::get('hallViews/{id}', [App\Http\Controllers\HallController::class, 'hallViews']);
        Route::post('search', [App\Http\Controllers\HallController::class, 'hallsAccordingQuestions']);
        Route::get('indexTypes', [App\Http\Controllers\HallTypeController::class, 'index']);
        //reservation
        Route::get('typeHalls/{id}', [App\Http\Controllers\HallTypeController::class, 'hallsOfType']);
        Route::post('addReservation', [App\Http\Controllers\ReservationController::class, 'addReservation']);
        Route::post('updateReservation', [App\Http\Controllers\ReservationController::class, 'updateReservation']);
        Route::delete('deleteReservation/{id}', [App\Http\Controllers\ReservationController::class, 'deleteReservation']);
        Route::get('userReservations', [App\Http\Controllers\ReservationController::class, 'userReservations']);
        Route::get('reservationDates/{id}', [App\Http\Controllers\ReservationController::class, 'reservationDates']);
        Route::get('userPreviousReservations', [App\Http\Controllers\ReservationController::class, 'userPreviousReservations']);
        Route::get('userUpcomingReservations', [App\Http\Controllers\ReservationController::class, 'userUpcomingReservations']);

    });
});
