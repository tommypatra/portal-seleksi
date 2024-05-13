<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AturGrup;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function index(AuthRequest $request)
    {
        $credentials = $request->validated();
        if (Auth::attempt($credentials)) {
            $user = User::where('email', $request->email)->first();

            $daftarAkses = daftarAkses($user->id);
            $token = $user->createToken('api_token')->plainTextToken;
            // $token = $user->createToken('api-token', ['user_id' => $user->id, 'user_group' => $daftarAkses])->plainTextToken;
            $akses_grup = $daftarAkses[0]->grup_id;
            $respon_data = [
                'message' => 'Login successful',
                'data' => $user,
                'access_token' => $token,
                'akses_grup' => $akses_grup,
                'daftar_akses' => $daftarAkses,
                'token_type' => 'Bearer',
            ];
            return response()->json($respon_data, 200);
        }
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    function tokenCek($grup_id)
    {
        $user_id = auth()->check() ? auth()->user()->id : null;
        if ($user_id) {
            // $token = auth()->user()->tokens->last();
            //mendapatkan user_group dari abilites
            // $daftar_akses = $token->abilities['user_group'];
            //mendapatkan user_group dari query 4 tabel kembali
            $daftar_akses = daftarAkses($user_id);
            $index = array_search($grup_id, array_column($daftar_akses, 'grup_id'));
            if ($index !== false) {
                return response()->json(['message' => 'token valid'], 200);
            }
        }
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    public function logout(Request $request)
    {
        $user_id = $request->user() ? $request->user()->id : null;
        if ($user_id) {
            if ($request->user()->tokens()->count() > 0) {
                $request->user()->tokens()->delete();
            }
        }
        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
