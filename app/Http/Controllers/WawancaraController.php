<?php

namespace App\Http\Controllers;

use App\Models\Pendaftar;
use App\Models\Wawancara;
use Illuminate\Http\Request;
use App\Http\Requests\WawancaraRequest;
use App\Http\Resources\WawancaraResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class WawancaraController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = Wawancara::with(['pendaftar.peserta.user', 'nilaiInterview.topikInterview', 'roleSeleksi.roleUser.user'])
            ->orderBy('role_seleksi_id', 'asc')
            ->orderBy('pendaftar_id', 'asc');

        // if ($request->filled('interview_id')) {
        //     $interview_id = $request->input('interview_id');
        //     $dataQuery->whereHas('Interview', function ($query) use ($seleksi_id) {
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
                return new WawancaraResource($item);
            });
            $data->setCollection($resourceCollection);
        } else {
            $data = ['data' => WawancaraResource::collection($dataQuery->get())];
        }

        return response()->json($data);
    }


    public function generatePembagianInterviewer(Request $request, $seleksi_id)
    {
        $dataQuery = Pendaftar::with(['Wawancara'])
            ->where('seleksi_id', $seleksi_id)
            ->where('verifikasi_lulus', true)
            ->orderBy('id', 'asc');
        $dataQuery->whereDoesntHave('Wawancara', function ($query) use ($seleksi_id) {
            $query->where('seleksi_id', $seleksi_id);
        });
        $data = $dataQuery->get();
        if ($data->isEmpty()) {
            return response()->json(['message' => 'semua sudah memiliki interviewer'], 500);
        }

        $interviewer = [];
        if ($request->filled('role_seleksi_id')) {
            $index = 0;
            $role_seleksi_id = $request->input('role_seleksi_id');
            $role_seleksi_count = count($role_seleksi_id);
            foreach ($data as $i => $pendaftar) {
                // echo $peserta->id . " [" . $role_seleksi_id[$index] . "]";
                $interviewer[] = ['pendaftar_id' => $pendaftar->id, 'role_seleksi_id' => $role_seleksi_id[$index]];
                $index = ($index + 1) % $role_seleksi_count;
            }
        }

        return $interviewer;
    }

    public function store(WawancaraRequest $request)
    {
        try {
            DB::beginTransaction();
            $seleksi = Wawancara::create($request->all());
            DB::commit();
            return response()->json(['message' => 'data baru berhasil dibuat', 'data' => $seleksi], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function update(WawancaraRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $wawancara = Wawancara::find($id);
            if (!$wawancara) {
                return response()->json(['message' => 'Data tidak ditemukan'], 404);
            }

            $wawancara->update($request->all());
            DB::commit();
            return response()->json(['message' => 'berhasil diperbarui', 'data' => $data], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }

    public function tukarPeserta(Request $request)
    {
        // return $request;

        try {
            $peserta_asal_id = $request->input('peserta_asal_id');
            $peserta_tujuan_id = $request->input('peserta_tujuan_id');
            if ($peserta_asal_id === $peserta_tujuan_id)
                return response()->json(['message' => 'asal dan tujuan tidak boleh sama'], 500);

            $wawancaraAsal = Wawancara::where('id', $peserta_asal_id)->first();
            if (!$wawancaraAsal)
                return response()->json(['message' => 'peserta asal tidak ditemukan'], 500);

            $wawancaraTujuan = Wawancara::where('id', $peserta_tujuan_id)->first();
            if (!$wawancaraTujuan)
                return response()->json(['message' => 'peserta tujuan tidak ditemukan'], 500);

            $pendaftar_asal_id = $wawancaraTujuan->pendaftar_id;
            $pendaftar_tujuan_id = $wawancaraAsal->pendaftar_id;

            DB::beginTransaction();
            $data[] = $wawancaraAsal->update(['pendaftar_id' => $pendaftar_asal_id]);
            $data[] = $wawancaraTujuan->update(['pendaftar_id' => $pendaftar_tujuan_id]);
            DB::commit();
            return response()->json(['message' => 'berhasil diperbarui', 'data' => $data], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $wawancara = Wawancara::find($id);
            if (!$wawancara) {
                return response()->json(['message' => 'Data tidak ditemukan'], 404);
            }

            $wawancara->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }
}
