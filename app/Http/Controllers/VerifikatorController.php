<?php

namespace App\Http\Controllers;

use App\Models\Seleksi;
use App\Models\BankSoal;
use App\Models\Pendaftar;

use App\Models\RoleSeleksi;
use App\Models\Verifikator;
use App\Models\UploadBerkas;
use Illuminate\Http\Request;
use App\Models\TopikInterview;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\VerifikatorRequest;
use App\Http\Resources\SoalWawancaraResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\VerifikatorResource;
use App\Http\Resources\VerifPesertaResource;

class VerifikatorController extends Controller
{
    public function index(Request $request)
    {
        $role_user_id = $request->input('role_user_id');
        $dataQuery = Seleksi::with([
            'roleSeleksi.roleUser.role',
            //untuk pastikan data peserta tampil hanya yg akan diverifikator  
            'pendaftar' => function ($query) use ($role_user_id) {
                $query->with(['pemeriksaSyarat.roleSeleksi'])
                    ->whereHas('pemeriksaSyarat.roleSeleksi', function ($query) use ($role_user_id) {
                        $query->where('role_user_id', $role_user_id);
                    });
            }
        ])
            //untuk pastikan data seleksi tampil hanya yg ditetapkan sebagai verifikator  
            ->whereHas('roleSeleksi', function ($query) use ($role_user_id) {
                $query->where('role_user_id', $role_user_id);
            })
            ->whereHas('roleSeleksi.roleUser.role', function ($query) {
                $query->where('nama', "Verifikator");
            })
            ->orderBy('nama', 'asc');


        if ($request->filled('search')) {
            $dataQuery->where('nama', 'like', '%' . $request->search . '%');
        }

        $limit = $request->filled('limit') ? $request->limit : 0;
        if ($limit) {
            $data = $dataQuery->paginate($limit);
            $resourceCollection = $data->map(function ($item) {
                return new VerifikatorResource($item);
            });
            $data->setCollection($resourceCollection);
        } else {
            $data = ['data' => VerifikatorResource::collection($dataQuery->get())];
        }

        return response()->json($data);
    }

    // public function verifikasiPeserta() {}

    // 'wawancara.nilaiInterview.topikInterview.bankSoal',
    // 'wawancara.role_seleksi.role_user.user',

    public function verifikasiPeserta(Request $request)
    {
        $role_user_id = $request->input('role_user_id');
        $seleksi_id = $request->input('seleksi_id');
        $dataQuery = Pendaftar::with([
            'seleksi.syarat.uploadBerkas',
            'pemeriksaSyarat.roleSeleksi',
            'peserta.user',
            'peserta.subInstitusi',
        ])
            //untuk pastikan data seleksi tampil hanya yg ditetapkan sebagai verifikator  
            ->where('seleksi_id', $seleksi_id)
            ->whereHas('pemeriksaSyarat.roleSeleksi', function ($query) use ($role_user_id) {
                $query->where('role_user_id', $role_user_id);
            })
            ->orderBy('id', 'asc');


        if ($request->filled('search')) {
            $dataQuery->where('nama', 'like', '%' . $request->search . '%');
        }

        $limit = $request->filled('limit') ? $request->limit : 0;
        if ($limit) {
            $data = $dataQuery->paginate($limit);
            $resourceCollection = $data->map(function ($item) {
                return new VerifPesertaResource($item);
            });
            $data->setCollection($resourceCollection);
        } else {
            $data = ['data' => VerifPesertaResource::collection($dataQuery->get())];
        }

        return response()->json($data);
    }


    public function jumlahDataVerifikator(Request $request)
    {
        $dataQuery = Verifikator::with(['pendaftar.pemeriksaSyarat', 'pendaftar.wawancara', 'roleVerifikator.roleUser.role'])->orderBy('nama', 'asc');
        $verfikator_id = $request->input('Verifikator_id');
        $tahun = $request->input('tahun');

        if ($request->filled('Verifikator_id')) {
            $dataQuery->where('id', $verfikator_id);
        }

        if ($request->filled('tahun')) {
            $dataQuery->where('tahun', $tahun);
        }

        if (!$verfikator_id && !$tahun) {
            $dataQuery->where('tahun', $tahun);
        }

        $data = $dataQuery->get();

        $result = [];
        foreach ($data as $i => $verfikator) {
            $jumlahPendaftar = 0;
            $jumlahLulusBerkas = 0;
            $jumlahPemeriksaSyarat = 0;
            $jumlahWawancara = 0;

            $roleCounts = [
                'administrator' => 0,
                'verifikator' => 0,
                'interviewer' => 0
            ];

            $result[$i]['id_Verifikator'] = $verfikator->id;
            $result[$i]['tahun'] = $verfikator->tahun;
            $result[$i]['nama'] = $verfikator->nama;
            foreach ($verfikator->pendaftar as $pendaftar) {
                $jumlahPendaftar++;
                if ($pendaftar->pemeriksaSyarat->isNotEmpty()) {
                    $jumlahPemeriksaSyarat++;
                    // return $pendaftar->pemeriksaSyarat;
                    $pemeriksa = $pendaftar->pemeriksaSyarat->first();
                    if ($pemeriksa->is_valid === 1) {
                        $jumlahLulusBerkas++;
                    }
                }
                if ($pendaftar->wawancara->isNotEmpty()) {
                    $jumlahWawancara++;
                }
            }

            // Hitung jumlah masing-masing role
            foreach ($verfikator->roleVerifikator as $role_verifikator) {
                $role = strtolower($role_verifikator->roleUser->role->nama);
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

    public function store(VerifikatorRequest $request)
    {
        try {
            DB::beginTransaction();
            $verfikator = Verifikator::create($request->all());
            DB::commit();
            return response()->json(['message' => 'data baru berhasil dibuat', 'data' => $verfikator], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function show(Verifikator $verfikator)
    {
        return response()->json(['data' => $verfikator], 200);
    }

    public function update(VerifikatorRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $verifikator = Verifikator::findOrFail($id);
            $verifikator->update($request->all());
            DB::commit();
            return response()->json(['message' => 'berhasil diperbarui', 'data' => $verfikator], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }

    public function verifikasiDokumen(Request $request, $id)
    {
        // Aturan validasi
        $rules = [
            'id' => 'required|integer',
            'verifikasi_valid' => 'required|integer',
        ];
        $rules['verifikasi_keterangan'] = intval($request->input('verifikasi_valid')) ? 'nullable|string' : 'required|string';

        // Validasi data
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();
            $upload_berkas = UploadBerkas::findOrFail($id);
            $upload_berkas->update($request->all());
            DB::commit();
            return response()->json(['message' => 'berhasil diperbarui', 'data' => $upload_berkas], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }

    public function kesimpulanVerifikasiDokumen(Request $request, $id)
    {
        // Aturan validasi
        $rules = [
            'id' => 'required|integer',
            'verifikasi_lulus' => 'required|integer',
        ];
        $rules['verifikasi_keterangan'] = intval($request->input('verifikasi_lulus')) ? 'nullable|string' : 'required|string';

        // Validasi data
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();
            $pendaftar = Pendaftar::findOrFail($id);
            $pendaftar->update($request->all());
            DB::commit();
            return response()->json(['message' => 'berhasil diperbarui', 'data' => $pendaftar], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $verifikator = Verifikator::findOrFail($id);
            $verfikator->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat menghapus : ' . $e->getMessage()], 500);
        }
    }
}
