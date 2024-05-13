<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InstitusiController;
use App\Http\Controllers\JenisController;
use App\Http\Controllers\SeleksiController;
use App\Http\Controllers\SyaratController;
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

Route::post('/auth-cek', [AuthController::class, 'index']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/token-cek/{grup_id}', [AuthController::class, 'tokenCek']);

    Route::resource('seleksi', SeleksiController::class);
    Route::resource('syarat', SyaratController::class);
    Route::resource('jenis', JenisController::class);
    Route::resource('institusi', InstitusiController::class);
});
