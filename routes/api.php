<?php

use App\Helpers\Func;
use Illuminate\Http\Request;
use App\Helpers\GetterSetter;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\auth\LoginController;
use App\Http\Controllers\api\master\UserController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\ReservasiController;
use App\Models\Reseravasi;

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
//login
Route::post('/login', [LoginController::class, 'login']);

//pendaftaran
Route::get('/pendaftaran/get', [PendaftaranController::class, 'data']);
Route::post('/pendaftaran/store', [PendaftaranController::class, 'store']);
//Route::update('/pendaftaran/update', [PendaftaranController::class, 'update']);
Route::delete('/pendaftaran/delete', [PendaftaranController::class, 'delete']);

//reservasi
Route::get('/reservasi/get', [ReservasiController::class, 'data']);
Route::post('/reservasi/store', [ReservasiController::class, 'store']);
//Route::put('/pendaftaran/update', [ReservasiController::class, 'update']);
Route::delete('/reservasi/delete', [ReservasiController::class, 'delete']);


Route::middleware(['check.token'])->group(function () {   
    // User Manager
    Route::post('/user/get', [UserController::class, 'data']);
    Route::post('/user/store', [UserController::class, 'store']);
    Route::post('/user/update', [UserController::class, 'update']);
    Route::post('/user/delete', [UserController::class, 'delete']);
    

});
