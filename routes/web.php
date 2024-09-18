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
Route::get('/jadwal-seleksi', [WebAppController::class, 'jadwalSeleksi'])->name('jadwal-seleksi');

//untuk admin
Route::get('/seleksi', [WebAppController::class, 'seleksi'])->name('seleksi');
Route::get('/institusi', [WebAppController::class, 'institusi'])->name('institusi');
Route::get('/akun', [WebAppController::class, 'akun'])->name('akun');
Route::get('/pengaturan/{seleksi_id}', [WebAppController::class, 'pengaturan'])->name('pengaturan');
Route::get('/institusi-detail/{institusi_id}', [WebAppController::class, 'institusiDetail'])->name('institusi-detail');
Route::get('/instrumen-wawancara/{seleksi_id}', [WebAppController::class, 'instrumenWawancara'])->name('instrumen-wawancara');


//untuk veifikator
Route::get('/verifikator', [WebAppController::class, 'verifikator'])->name('verifikator');

//untuk interviewer
Route::get('/interviewer', [WebAppController::class, 'interviewer'])->name('interviewer');
Route::get('/daftar-peserta-interview/{seleksi_id}', [WebAppController::class, 'daftarPesertaInterview'])->name('daftar-peserta-interview');
