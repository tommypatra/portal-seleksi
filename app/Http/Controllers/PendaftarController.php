<?php

namespace App\Http\Controllers;

use App\Http\Requests\PendaftarRequest;
use App\Models\Pendaftar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PendaftarController extends Controller
{
    public function store(PendaftarRequest $request)
    {

        try {
            DB::beginTransaction();
            //verifikasi status pendaftaran
            if (!statusJadwal($request->seleksi_id)->statusDaftar) {
                return response()->json(['message' => 'pendaftaran sudah tertutup'], 500);
            }
            //verifikasi jika sudah mendaftar 2 kali dalam 1 akun
            $pendaftar = Pendaftar::where("peserta_id", $request->peserta_id)
                ->where("tahun", $request->tahun)
                ->whereNull("verifikasi_lulus")->first();
            if ($pendaftar) {
                return response()->json(['message' => 'tidak bisa mendaftar lebih dari 1 seleksi'], 404);
            }

            $seleksi = Pendaftar::create($request->all());
            DB::commit();
            return response()->json(['message' => 'data baru berhasil dibuat', 'data' => $seleksi], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Pendaftar $pendaftar)
    {
        try {
            DB::beginTransaction();
            //verifikasi status pendaftaran
            if (!statusJadwal($pendaftar->seleksi_id)->statusDaftar) {
                return response()->json(['message' => 'pendaftaran sudah tertutup'], 500);
            }
            // $pendaftar = Pendaftar::find($id);
            $pendaftar->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat mengapus : ' . $e->getMessage()], 500);
        }
    }
}
