<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\Peserta;
use App\Models\Seleksi;
use App\Models\RoleUser;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

if (!function_exists('daftarAkses')) {
    function daftarAkses($user_id)
    {
        $listAkses = [];
        $getUser = User::with(['roleUser.role', 'peserta'])->where('id', $user_id)->first();
        // dd($getUser);
        if (is_null($getUser)) {
            return [];
        }

        foreach ($getUser->roleUser as $i => $dt) {
            $listAkses[] = ['grup_id' => $dt->role_id, 'nama' => $dt->role->nama, 'role_user_id' => $dt->id];
        }

        foreach ($getUser->peserta as $i => $dt) {
            $listAkses[] = ['grup_id' => 0, 'nama' => 'Peserta ' . $dt->noid, 'role_user_id' => $dt->id];
        }
        // dd($listAkses);
        return json_decode(json_encode($listAkses));
    }
}

if (!function_exists('dataPeserta')) {
    function dataPeserta($user_id)
    {
        $dt = Peserta::with(['user'])->where('user_id', $user_id)->get();
        if ($dt->isEmpty()) {
            return [];
        }
        return json_decode(json_encode($dt));
    }
}

if (!function_exists('cekStatusJadwal')) {
    function cekStatusJadwal($start, $end)
    {
        $today = date('Y-m-d'); // Tanggal hari ini dalam format Y-m-d

        // Ubah format tanggal mulai dan selesai ke Y-m-d
        $startDate = date('Y-m-d', strtotime($start));
        $endDate = date('Y-m-d', strtotime($end));

        return ($today >= $startDate && $today <= $endDate);
    }
}

if (!function_exists('statusJadwal')) {
    function statusJadwal($id)
    {
        $statusDaftar = false;
        $statusVerifikasi = false;
        $dt = Seleksi::where('id', $id)->first();
        if ($dt) {
            $statusDaftar = cekStatusJadwal($dt->daftar_mulai, $dt->daftar_selesai);
            $statusVerifikasi = cekStatusJadwal($dt->verifikasi_mulai, $dt->verifikasi_selesai);
        }

        return json_decode(json_encode(['statusDaftar' => $statusDaftar, 'statusVerifikasi' => $statusVerifikasi]));
    }
}

// if (!function_exists('is_admin')) {
//     function is_admin()
//     {
//         $user_id = auth()->id();
//         try {
//             $akses = daftarAkses($user_id);
//             $isAdmin = $akses->aturgrup->contains('grup_id', 1);
//             return $isAdmin;
//         } catch (\Exception $e) {
//             return false;
//         }
//     }
// }

// if (!function_exists('is_editor')) {
//     function is_editor()
//     {
//         $user_id = auth()->id();
//         try {
//             $akses = daftarAkses($user_id);
//             $is_editor = $akses->aturgrup->contains('grup_id', 2);
//             return $is_editor;
//         } catch (\Exception $e) {
//             return false;
//         }
//     }
// }

// if (!function_exists('getUserIdFromToken')) {
//     function getUserIdFromToken()
//     {
//         try {
//             $user = Auth::guard('sanctum')->user();
//             if ($user) {
//                 return $user->id;
//             } else {
//                 return null;
//             }
//         } catch (Exception $e) {
//             return null;
//         }
//     }
// }

// if (!function_exists('daftarAkses')) {
//     function daftarAkses($user_id)
//     {
//         return User::with(['aturgrup.grup'])->where('id', $user_id)->firstOrFail();
//     }
// }

if (!function_exists('anchor')) {
    function anchor($url, $text)
    {
        return '<a href="' . $url . '">' . $text . '</a>';
    }
}

if (!function_exists('dbDateTimeFormat')) {
    function dbDateTimeFormat($waktuDb, $format = 'Y-m-d H:i:s')
    {
        return Carbon::parse($waktuDb)->timezone('Asia/Makassar')->format($format);
    }
}

if (!function_exists('generateUniqueFileName')) {
    function generateUniqueFileName()
    {
        return $randomString = time() . Str::random(22);
    }
}

if (!function_exists('generateSlug')) {
    function generateSlug($judul, $waktu)
    {
        $disallowed_chars = array(
            '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+', '=', '{', '}', '[', ']', '|', '\\', ';', ':', '"', '<', '>', ',', '.', '/', '?',
            ' ', "'", ' '
        );
        $judul = str_replace(' ', '-', $judul);
        $judul = str_replace($disallowed_chars, ' ', $judul);
        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $judul));

        $timestamp = strtotime($waktu);

        $tgl = date('y', $timestamp) + date('j', $timestamp) + date('n', $timestamp) + date('w', $timestamp);
        $waktu = date('H', $timestamp) + date('i', $timestamp);
        // $tanggal = date('ymd', strtotime($waktu));
        // $waktu = date('his', strtotime($waktu));
        // $tanggal = date('ymd', strtotime($waktu));
        // $waktu = date('his', strtotime($waktu));

        $generateWaktu = ($tgl + $waktu + rand(1, 999)) . '-' . date('s', $timestamp);
        // $finalSlug = date('ymd', $timestamp) . '-' . $slug . '-' . $generateWaktu;
        $finalSlug = $slug . '-' . $generateWaktu;
        return $finalSlug;
    }
}

if (!function_exists('ukuranFile')) {
    function ukuranFile($size)
    {
        $satuan = ['B', 'KB', 'MB', 'GB', 'TB'];
        for ($i = 0; $size >= 1024 && $i < 4; $i++) {
            $size /= 1024;
        }
        return round($size, 2) . ' ' . $satuan[$i];
    }
}

if (!function_exists('uploadFile')) {
    function uploadFile($request, $reqFileName = 'file', $storagePath = null, $fileName = null)
    {
        try {
            $uploadedFile = $request->file($reqFileName);
            if (!$uploadedFile->isValid()) {
                return false;
            }

            $originalFileName = $uploadedFile->getClientOriginalName();
            $ukuranFile = $uploadedFile->getSize();
            $tipeFile = $uploadedFile->getMimeType();
            $ext = $uploadedFile->getClientOriginalExtension();
            if (!$storagePath)
                $storagePath = 'uploads/' . date('Y') . '/' . date('m');

            if (!File::isDirectory(public_path($storagePath))) {
                File::makeDirectory(public_path($storagePath), 0755, true);
            }

            if (!$fileName)
                $fileName = generateUniqueFileName();
            $fileName .= '.' . $ext;

            $uploadedFile->move(public_path($storagePath), $fileName);
            $fileFullPath = public_path($storagePath . '/' . $fileName);
            chmod($fileFullPath, 0755);
            $path = $storagePath . '/' . $fileName;
            return [
                'path' => $path,
                'jenis' => $tipeFile,
                'ukuran' => ($ukuranFile / 1024),
            ];
        } catch (\Exception $e) {
            return 'Gagal mengunggah file. ' . $e->getMessage();
        }
    }
}

if (!function_exists('updateTokenUsed')) {
    function updateTokenUsed()
    {
        if (auth()->check()) {
            $user = auth()->user();
            $token = $user->tokens->last();
            if ($token) {
                $token->forceFill([
                    'created_at' => now(),
                    'last_used_at' => now(),
                ])->save();
            }
        }
    }
}

if (!function_exists('ambilKata')) {
    function ambilKata($text, $limit = 25)
    {
        $text = strip_tags($text);
        $words = preg_split("/[\s,]+/", $text);
        $shortenedText = implode(' ', array_slice($words, 0, $limit));
        if (str_word_count($text) > $limit) {
            $shortenedText .= '...';
        }
        return $shortenedText;
    }
}
