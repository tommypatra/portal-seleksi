<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class WebAppController extends Controller
{

    public function setSession(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);
        $credentials = $request->only('email', 'password');
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Kombinasi email dan password tidak valid',
            ], 404);
        }
        $user = Auth::user();
        Auth::login($user);
        $respon_data = [
            'message' => 'Proses login selesai dilakukan',
        ];
        return response()->json($respon_data, 200);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    public function session()
    {
        dd(auth()->user());
    }

    public function login()
    {
        return view('login');
    }

    public function dashboard()
    {
        return view('dashboard.depan');
    }

    public function seleksi()
    {
        return view('dashboard.seleksi');
    }

    public function akun()
    {
        return view('dashboard.akun');
    }

    public function institusi()
    {
        return view('dashboard.institusi');
    }

    public function pengaturan($seleksi_id)
    {
        return view('dashboard.pengaturan', ['seleksi_id' => $seleksi_id]);
    }

    public function institusiDetail($institusi_id)
    {
        return view('dashboard.institusi_detail', ['institusi_id' => $institusi_id]);
    }

    public function jadwalSeleksi()
    {
        return view('dashboard.jadwal_seleksi');
    }

    public function interviewer()
    {
        return view('dashboard.interviewer');
    }

    public function daftarPesertaInterview($seleksi_id)
    {
        return view('dashboard.daftar_peserta_interview', ['seleksi_id' => $seleksi_id]);
    }

    public function verifikator()
    {
        return view('dashboard.verifikator');
    }

    public function instrumenWawancara($seleksi_id)
    {
        return view('dashboard.instrumen_wawancara', ['seleksi_id' => $seleksi_id]);
    }
}
