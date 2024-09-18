<?php

namespace App\Http\Controllers;

use App\Http\Requests\BagiPemeriksaRequest;
use App\Http\Requests\PemeriksaSyaratRequest;
use App\Http\Resources\PemeriksaSyaratResource;
use App\Models\PemeriksaSyarat;
use App\Models\Pendaftar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PemeriksaSyaratController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = PemeriksaSyarat::with(['pendaftar.peserta.user', 'roleSeleksi.roleUser.user'])
            ->orderBy('role_seleksi_id', 'asc')
            ->orderBy('pendaftar_id', 'asc');


        // if ($request->filled('role_user_id')) {
        //     $role_user_id = $request->input('role_user_id');
        //     $dataQuery->whereHas('roleSeleksi', function ($query) use ($seleksi_id) {
        //         $dataQuery->where('role_user_id', '!=', $role_user_id);
        //     });
        // }

        if ($request->filled('seleksi_id')) {
            $seleksi_id = $request->input('seleksi_id');
            $dataQuery->whereHas('pendaftar', function ($query) use ($seleksi_id) {
                $query->where('seleksi_id', $seleksi_id);
            });
        }

        $limit = $request->filled('limit') ? $request->limit : 0;
        if ($limit) {
            $data = $dataQuery->paginate($limit);
            $resourceCollection = $data->map(function ($item) {
                return new PemeriksaSyaratResource($item);
            });
            $data->setCollection($resourceCollection);
        } else {
            $data = ['data' => PemeriksaSyaratResource::collection($dataQuery->get())];
        }

        return response()->json($data);
    }



    public function generatePembagianVerifikator(Request $request, $seleksi_id)
    {
        $dataQuery = Pendaftar::with(['pemeriksaSyarat'])
            ->where('seleksi_id', $seleksi_id)
            ->orderBy('id', 'asc');
        $dataQuery->whereDoesntHave('pemeriksaSyarat', function ($query) use ($seleksi_id) {
            $query->where('seleksi_id', $seleksi_id);
        });
        $data = $dataQuery->get();
        if ($data->isEmpty()) {
            return response()->json(['message' => 'semua sudah memiliki verifikator'], 500);
        }

        $post_pemeriksa_syarat = [];
        if ($request->filled('role_seleksi_id')) {
            $index = 0;
            $role_seleksi_id = $request->input('role_seleksi_id');
            $role_count = count($role_seleksi_id);
            foreach ($data as $i => $pendaftar) {
                // echo $peserta->id . " [" . $role_seleksi_id[$index] . "]";
                $post_pemeriksa_syarat[] = ['pendaftar_id' => $pendaftar->id, 'role_seleksi_id' => $role_seleksi_id[$index]];
                $index = ($index + 1) % $role_count;
            }
        }

        return $post_pemeriksa_syarat;
    }

    public function store(PemeriksaSyaratRequest $request)
    {
        try {
            DB::beginTransaction();
            $seleksi = PemeriksaSyarat::create($request->all());
            DB::commit();
            return response()->json(['message' => 'data baru berhasil dibuat', 'data' => $seleksi], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function update(PemeriksaSyaratRequest $request, PemeriksaSyarat $pemeriksaSyarat)
    {
        try {
            DB::beginTransaction();
            $pemeriksaSyarat->update($request->all());
            DB::commit();
            return response()->json(['message' => 'berhasil diperbarui', 'data' => $data], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }

    public function tukarPeserta(Request $request)
    {
        try {
            $peserta_asal_id = $request->input('peserta_asal_id');
            $peserta_tujuan_id = $request->input('peserta_tujuan_id');
            if ($peserta_asal_id === $peserta_tujuan_id)
                return response()->json(['message' => 'asal dan tujuan tidak boleh sama'], 500);

            $pemeriksaSyaratAsal = PemeriksaSyarat::where('id', $peserta_asal_id)->first();
            if (!$pemeriksaSyaratAsal)
                return response()->json(['message' => 'peserta asal tidak ditemukan'], 500);

            $pemeriksaSyaratTujuan = PemeriksaSyarat::where('id', $peserta_tujuan_id)->first();
            if (!$pemeriksaSyaratTujuan)
                return response()->json(['message' => 'peserta tujuan tidak ditemukan'], 500);

            $pendaftar_asal_id = $pemeriksaSyaratTujuan->pendaftar_id;
            $pendaftar_tujuan_id = $pemeriksaSyaratAsal->pendaftar_id;

            DB::beginTransaction();
            $data[] = $pemeriksaSyaratAsal->update(['pendaftar_id' => $pendaftar_asal_id]);
            $data[] = $pemeriksaSyaratTujuan->update(['pendaftar_id' => $pendaftar_tujuan_id]);
            DB::commit();
            return response()->json(['message' => 'berhasil diperbarui', 'data' => $data], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }

    public function destroy(PemeriksaSyarat $pemeriksaSyarat)
    {
        try {
            DB::beginTransaction();
            $pemeriksaSyarat->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }
}
