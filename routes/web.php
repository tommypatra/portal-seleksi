<?php

use App\Http\Controllers\WebAppController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [WebAppController::class, 'login']);
Route::get('/login', [WebAppController::class, 'login'])->name('login');
Route::post('/logout', [WebAppController::class, 'logout']);
Route::get('/dashboard', [WebAppController::class, 'dashboard'])->name('dashboard');

//untuk admin
Route::get('/seleksi', [WebAppController::class, 'seleksi'])->name('seleksi');
Route::get('/institusi', [WebAppController::class, 'institusi'])->name('institusi');
Route::get('/pengaturan/{seleksi_id}', [WebAppController::class, 'pengaturan'])->name('pengaturan');
