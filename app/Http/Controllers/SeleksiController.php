<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeleksiRequest;
use App\Http\Resources\SeleksiResource;
use App\Http\Resources\JadwalSeleksiResource;
use App\Models\Seleksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SeleksiController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = Seleksi::with(['jenis', 'user', 'pendaftar'])->orderBy('nama', 'asc');
        if ($request->filled('search')) {
            $dataQuery->where('nama', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('seleksi_id')) {
            $seleksi_id = $request->input('seleksi_id');
            $query->where('id', $seleksi_id);
        }

        if ($request->filled('role_user_id')) {
            $role_user_id = $request->input('role_user_id');
            $dataQuery->whereHas('pendaftar', function ($query) use ($role_user_id) {
                $query->where('role_user_id', $role_user_id);
            });
        }

        $limit = $request->filled('limit') ? $request->limit : 0;
        if ($limit) {
            $data = $dataQuery->paginate($limit);
            $resourceCollection = $data->map(function ($item) {
                return new SeleksiResource($item);
            });
            $data->setCollection($resourceCollection);
        } else {
            $data = ['data' => SeleksiResource::collection($dataQuery->get())];
        }

        return response()->json($data);
    }


    public function jadwalSeleksi(Request $request)
    {
        $role_user_id = $request->input('role_user_id');
        $dataQuery = Seleksi::with([
            'jenis',
            'user',
            'pendaftar' => function ($query) use ($role_user_id) {
                $query->where('id', $role_user_id)->with(['uploadBerkas']);
            },
            'syarat' => function ($query) {
                $query->orderBy('is_wajib', 'desc')->orderBy('nama', 'asc');
            }
        ])->where('is_publish', 1)->orderBy('nama', 'asc');

        if ($request->filled('seleksi_id')) {
            $dataQuery->where('id', $request->input('seleksi_id'));
        }
        if ($request->filled('search')) {
            $dataQuery->where('nama', 'like', '%' . $request->search . '%');
        }


        $limit = $request->filled('limit') ? $request->limit : 0;
        if ($limit) {
            $data = $dataQuery->paginate($limit);
            $resourceCollection = $data->map(function ($item) {
                return new JadwalSeleksiResource($item);
            });
            $data->setCollection($resourceCollection);
        } else {
            $data = ['data' => JadwalSeleksiResource::collection($dataQuery->get())];
        }

        return response()->json($data);
    }

    public function jumlahDataSeleksi(Request $request)
    {
        $dataQuery = Seleksi::with(['pendaftar.pemeriksaSyarat', 'pendaftar.wawancara', 'roleSeleksi.roleUser.role'])->orderBy('nama', 'asc');
        $seleksi_id = $request->input('seleksi_id');
        $tahun = $request->input('tahun');

        if ($request->filled('seleksi_id')) {
            $dataQuery->where('id', $seleksi_id);
        }

        if ($request->filled('tahun')) {
            $dataQuery->where('tahun', $tahun);
        }

        if (!$seleksi_id && !$tahun) {
            $dataQuery->where('tahun', $tahun);
        }

        $data = $dataQuery->get();

        $result = [];
        foreach ($data as $i => $seleksi) {
            $jumlahPendaftar = 0;
            $jumlahLulusBerkas = 0;
            $jumlahPemeriksaSyarat = 0;
            $jumlahWawancara = 0;

            $roleCounts = [
                'administrator' => 0,
                'verifikator' => 0,
                'interviewer' => 0
            ];

            $result[$i]['id_seleksi'] = $seleksi->id;
            $result[$i]['tahun'] = $seleksi->tahun;
            $result[$i]['nama'] = $seleksi->nama;
            // $result[$i]['data'] = $seleksi;
            foreach ($seleksi->pendaftar as $pendaftar) {
                $jumlahPendaftar++;
                if ($pendaftar->pemeriksaSyarat->isNotEmpty()) {
                    $jumlahPemeriksaSyarat++;
                    // return $pendaftar->pemeriksaSyarat;
                    $pemeriksa = $pendaftar->pemeriksaSyarat->first();
                }

                if ($pendaftar->verifikasi_lulus === 1) {
                    $jumlahLulusBerkas++;
                }

                if ($pendaftar->wawancara->isNotEmpty()) {
                    $jumlahWawancara++;
                }
            }

            // Hitung jumlah masing-masing role
            foreach ($seleksi->roleSeleksi as $role_seleksi) {
                $role = strtolower($role_seleksi->roleUser->role->nama);
                if (isset($roleCounts[$role])) {
                    $roleCounts[$role]++;
                }
            }
            $result[$i]['jumlah']['pendaftar'] = $jumlahPendaftar;
            $result[$i]['jumlah']['lulusBerkas'] = $jumlahLulusBerkas;
            $result[$i]['jumlah']['adaVerifikator'] = $jumlahPemeriksaSyarat;
            $result[$i]['jumlah']['belumAdaVerifikator'] = ($jumlahPendaftar - $jumlahPemeriksaSyarat);
            $result[$i]['jumlah']['pewawancara'] = $jumlahWawancara;
            $result[$i]['jumlah']['belumAdaPewawancara'] = ($jumlahLulusBerkas - $jumlahWawancara);
            $result[$i]['role'] = $roleCounts;
        }
        return response()->json($result);
    }

    public function store(SeleksiRequest $request)
    {
        try {
            DB::beginTransaction();
            $seleksi = Seleksi::create($request->all());
            DB::commit();
            return response()->json(['message' => 'data baru berhasil dibuat', 'data' => $seleksi], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function show(Seleksi $seleksi)
    {
        return response()->json(['data' => $seleksi], 200);
    }

    public function update(SeleksiRequest $request, Seleksi $seleksi)
    {
        try {
            DB::beginTransaction();
            $seleksi->update($request->all());
            DB::commit();
            return response()->json(['message' => 'berhasil diperbarui', 'data' => $seleksi], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Seleksi $seleksi)
    {
        try {
            DB::beginTransaction();
            $seleksi->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }
}
