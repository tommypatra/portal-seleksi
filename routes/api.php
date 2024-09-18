<?php

use App\Models\Wawancara;
use Illuminate\Http\Request;
use App\Models\TopikInterview;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JenisController;
use App\Http\Controllers\SyaratController;
use App\Http\Controllers\SeleksiController;
use App\Http\Controllers\BankSoalController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\InstitusiController;
use App\Http\Controllers\PendaftarController;
use App\Http\Controllers\WawancaraController;
use App\Http\Controllers\RoleSeleksiController;
use App\Http\Controllers\SeleksiAsalController;
use App\Http\Controllers\VerifikatorController;
use App\Http\Controllers\SubInstitusiController;
use App\Http\Controllers\UploadBerkasController;
use App\Http\Controllers\JadwalSeleksiController;
use App\Http\Controllers\TopikInterviewController;
use App\Http\Controllers\PemeriksaSyaratController;
use App\Http\Controllers\InterviewerController;

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

Route::post('auth-cek', [AuthController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::resource('seleksi-asal', SeleksiAsalController::class);
    Route::get('token-cek/{grup_id}', [AuthController::class, 'tokenCek']);
    Route::get('sub-institusi-tersedia/{seleksi_id}', [SeleksiAsalController::class, 'subInstitusiTersedia']);

    Route::resource('seleksi', SeleksiController::class);
    Route::resource('syarat', SyaratController::class);
    Route::resource('bank-soal', BankSoalController::class);
    Route::resource('jenis', JenisController::class);

    Route::resource('institusi', InstitusiController::class);
    Route::resource('verifikator', VerifikatorController::class);
    Route::get('verifikasi-peserta', [VerifikatorController::class, 'verifikasiPeserta']);

    Route::resource('interviewer', InterviewerController::class);
    Route::get('peserta-interviewer', [InterviewerController::class, 'daftarPeserta']);
    Route::get('penilaian-interview/{pendaftar_id}', [InterviewerController::class, 'penilaianInterview']);

    Route::resource('akun', AkunController::class);
    Route::get('akun-verifikator', [AkunController::class, 'akunVerifikator']);

    Route::post('akun-hakakses/{user_id}', [AkunController::class, 'hakAkses']);
    Route::delete('akun-akses/{id}', [AkunController::class, 'destroyAkses']);
    // Route::delete('akun-verifikator/{id}', [AkunController::class, 'destroyVerifikator']);
    // Route::delete('akun-peserta/{id}', [AkunController::class, 'destroyPeserta']);

    Route::resource('pemeriksa-syarat', PemeriksaSyaratController::class);
    Route::post('generate-pembagian-verifikator/{seleksi_id}', [PemeriksaSyaratController::class, 'generatePembagianVerifikator']);
    Route::put('tukar-peserta-verifikasi', [PemeriksaSyaratController::class, 'tukarPeserta']);



    Route::resource('wawancara-peserta', WawancaraController::class);
    Route::post('generate-pembagian-interviewer/{seleksi_id}', [WawancaraController::class, 'generatePembagianInterviewer']);
    Route::put('tukar-peserta-wawancara', [WawancaraController::class, 'tukarPeserta']);

    Route::resource('kategori', KategoriController::class);
    Route::resource('instrumen-wawancara', TopikInterviewController::class);
    Route::resource('sub-institusi', SubInstitusiController::class);
    Route::resource('seleksi-asal', SeleksiAsalController::class);
    Route::resource('role-seleksi', RoleSeleksiController::class);
    Route::get('role-tersedia/{seleksi_id}/{role}', [RoleSeleksiController::class, 'roleTersedia']);

    //untuk pendaftar
    Route::get('jadwal-seleksi', [SeleksiController::class, 'jadwalSeleksi']);
    Route::get('jumlah-data-seleksi', [SeleksiController::class, 'jumlahDataSeleksi']);
    Route::post('pendaftar', [PendaftarController::class, 'store']);
    Route::delete('pendaftar/{pendaftar}', [PendaftarController::class, 'destroy']);

    Route::get('upload-berkas', [UploadBerkasController::class, 'index']);
    Route::post('upload-berkas', [UploadBerkasController::class, 'store']);
    Route::delete('upload-berkas/{id}', [UploadBerkasController::class, 'destroy']);

    //untuk update verifikasi
    Route::put('verifikasi-dokumen/{id}', [VerifikatorController::class, 'verifikasiDokumen']);
    Route::put('kesimpulan-verifikasi-dokumen/{id}', [VerifikatorController::class, 'kesimpulanVerifikasiDokumen']);
});
