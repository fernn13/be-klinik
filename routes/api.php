<?php

use App\Helpers\Func;
use Illuminate\Http\Request;
use App\Helpers\GetterSetter;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\auth\LoginController;
use App\Http\Controllers\api\master\UserController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\AntrianController;
use App\Http\Controllers\PemeriksaanController;
use App\Http\Controllers\DiagnosaController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\ResepObatController;
use App\Http\Controllers\PenebusanObatController;

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


// Master Diagnosa
Route::get('/diagnosa', [DiagnosaController::class, 'index']);
Route::put('/diagnosa/update', [DiagnosaController::class, 'update']);
Route::post('/diagnosa', [DiagnosaController::class, 'store']);
Route::delete('/diagnosa/{id}', [DiagnosaController::class, 'destroy']);
Route::post('/diagnosa/import', [DiagnosaController::class, 'import']);

// Obat
Route::prefix('obat')->group(function () {
    Route::get('/', [ObatController::class, 'index']);
    Route::post('/', [ObatController::class, 'store']);
    Route::put('/update', [ObatController::class, 'update']);
    Route::delete('/{id}', [ObatController::class, 'destroy']);
    Route::post('/import', [ObatController::class, 'import']);
});

//login
Route::post('/login', [LoginController::class, 'login']);

//pendaftaran
Route::get('/pendaftaran/get', [PendaftaranController::class, 'data']);
Route::post('/pendaftaran/store', [PendaftaranController::class, 'store']);
Route::post('/pendaftaran/update', [PendaftaranController::class, 'update']);
Route::delete('/pendaftaran/delete', [PendaftaranController::class, 'delete']);
Route::get('pendaftaran/show', [PendaftaranController::class, 'show']);


//reservasi
Route::get('/reservasi/get', [ReservasiController::class, 'data']);
Route::post('/reservasi/store', [ReservasiController::class, 'store']);
Route::post('/reservasi/update', [ReservasiController::class, 'update']);
Route::get('/reservasi/show', [ReservasiController::class, 'show']);
Route::delete('/reservasi/delete', [ReservasiController::class, 'delete']);

//Antrian Pasien
Route::prefix('antrian')->group(function () {
    Route::get('/',          [AntrianController::class, 'index']);         // GET  /api/antrian
    Route::post('/store',    [AntrianController::class, 'store']);         // POST /api/antrian/store
    Route::patch('/{id}',    [AntrianController::class, 'updateStatus']);  // PATCH /api/antrian/{id}
});

//Pemeriksaan Pasien
Route::get('/pemeriksaan', [PemeriksaanController::class, 'index']);
Route::get('/pemeriksaan/{id}', [PemeriksaanController::class, 'show']);
Route::post('/pemeriksaan/{id}', [PemeriksaanController::class, 'store']);

// Resep Obat
Route::get('/resep', [ResepObatController::class, 'index']);
Route::get('/resep/{antrian_id}', [ResepObatController::class, 'show']);
Route::post('/resep/{antrian_id}', [ResepObatController::class, 'store']);
Route::delete('/resep/{antrian_id}', [ResepObatController::class, 'destroy']); // optional


// Penebusan Obat
Route::get('/penebusan-obat', [PenebusanObatController::class, 'index']);
Route::get('/resep-obat/{id}', [PenebusanObatController::class, 'show']);
Route::post('/penebusan-obat/lunas/{id}', [PenebusanObatController::class, 'updateStatus']);


Route::middleware(['check.token'])->group(function () {   
    // User Manager
    Route::post('/user/get', [UserController::class, 'data']);
    Route::post('/user/store', [UserController::class, 'store']);
    Route::post('/user/update', [UserController::class, 'update']);
    Route::post('/user/delete', [UserController::class, 'delete']);

});
