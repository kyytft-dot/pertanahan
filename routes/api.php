<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - SIMRS Awani Care
|--------------------------------------------------------------------------
*/

// Route untuk testing
Route::get('/', function () {
    return response()->json([
        'status' => true,
        'message' => 'API SIMRS Awani Care berjalan dengan baik!',
        'version' => '1.0.0',
        'timestamp' => now()
    ]);
});

/*
|--------------------------------------------------------------------------
| PROVINSI ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('provinsi')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\ProvinsiController::class, 'index']);
    Route::post('/', [\App\Http\Controllers\Api\ProvinsiController::class, 'store']);
    Route::get('/{id}', [\App\Http\Controllers\Api\ProvinsiController::class, 'show']);
    Route::put('/{id}', [\App\Http\Controllers\Api\ProvinsiController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\Api\ProvinsiController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| KOTA/KABUPATEN ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('kota-kabupaten')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\KotaKabupatenController::class, 'index']);
    Route::post('/', [\App\Http\Controllers\Api\KotaKabupatenController::class, 'store']);
    Route::get('/provinsi/{idProvinsi}', [\App\Http\Controllers\Api\KotaKabupatenController::class, 'getByProvinsi']);
    Route::get('/{id}', [\App\Http\Controllers\Api\KotaKabupatenController::class, 'show']);
    Route::put('/{id}', [\App\Http\Controllers\Api\KotaKabupatenController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\Api\KotaKabupatenController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| KECAMATAN ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('kecamatan')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\KecamatanController::class, 'index']);
    Route::post('/', [\App\Http\Controllers\Api\KecamatanController::class, 'store']);
    Route::get('/{id}', [\App\Http\Controllers\Api\KecamatanController::class, 'show']);
    Route::put('/{id}', [\App\Http\Controllers\Api\KecamatanController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\Api\KecamatanController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| DESA/KELURAHAN ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('desa')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\DesaKelurahanController::class, 'index']);
    Route::post('/', [\App\Http\Controllers\Api\DesaKelurahanController::class, 'store']);
    Route::get('/{id}', [\App\Http\Controllers\Api\DesaKelurahanController::class, 'show']);
    Route::put('/{id}', [\App\Http\Controllers\Api\DesaKelurahanController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\Api\DesaKelurahanController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| PENDUDUK ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('penduduk')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\PendudukController::class, 'index']);
    Route::post('/', [\App\Http\Controllers\Api\PendudukController::class, 'store']);
    Route::get('/{id}', [\App\Http\Controllers\Api\PendudukController::class, 'show']);
    Route::put('/{id}', [\App\Http\Controllers\Api\PendudukController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\Api\PendudukController::class, 'destroy']);
});
Route::get('/resources/views/leaflet.blade.php', function () {
    return view('/resources/views/leaflet.blade.php'); // atau view('folder.nama_file');
});