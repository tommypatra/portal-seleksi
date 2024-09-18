<?php

namespace App\Http\Controllers;

use App\Models\Seleksi;
use App\Models\Pendaftar;
use App\Models\Interviewer;

use App\Models\RoleSeleksi;
use App\Models\UploadBerkas;
use Illuminate\Http\Request;
use App\Models\TopikInterview;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\InterviewerRequest;
use App\Http\Resources\PendaftarResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\InterviewerResource;
use App\Http\Resources\VerifPesertaResource;
use App\Http\Resources\SoalWawancaraResource;
use App\Http\Resources\DaftarPesertaInterviewResource;

class InterviewerController extends Controller
{
    public function index(Request $request)
    {
        $role_user_id = $request->input('role_user_id');
        $dataQuery = Seleksi::with([
            'roleSeleksi.roleUser.role',
            //untuk pastikan data peserta tampil hanya yg akan diInterviewer  
            'pendaftar' => function ($query) use ($role_user_id) {
                $query->with(['wawancara.roleSeleksi'])
                    ->whereHas('wawancara.roleSeleksi', function ($query) use ($role_user_id) {
                        $query->where('role_user_id', $role_user_id);
                    });
            }
        ])
            //untuk pastikan data seleksi tampil hanya yg ditetapkan sebagai Interviewer  
            ->whereHas('roleSeleksi', function ($query) use ($role_user_id) {
                $query->where('role_user_id', $role_user_id);
            })
            ->whereHas('roleSeleksi.roleUser.role', function ($query) {
                $query->where('nama', "Interviewer");
            })
            ->orderBy('nama', 'asc');


        if ($request->filled('search')) {
            $dataQuery->where('nama', 'like', '%' . $request->search . '%');
        }

        $limit = $request->filled('limit') ? $request->limit : 0;
        if ($limit) {
            $data = $dataQuery->paginate($limit);
            $resourceCollection = $data->map(function ($item) {
                return new InterviewerResource($item);
            });
            $data->setCollection($resourceCollection);
        } else {
            $data = ['data' => InterviewerResource::collection($dataQuery->get())];
        }

        return response()->json($data);
    }


    public function daftarPeserta(Request $request)
    {
        $seleksi_id = $request->input('seleksi_id');
        $role_user_id = $request->input('role_user_id');
        $dataQuery = Pendaftar::with([
            'peserta.subInstitusi.institusi',
            'peserta.user',
            'wawancara.roleSeleksi.roleUser.user',
        ])
            ->where('seleksi_id', $seleksi_id)
            ->whereHas('wawancara.roleSeleksi', function ($query) use ($role_user_id) {
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
                return new DaftarPesertaInterviewResource($item);
            });
            $data->setCollection($resourceCollection);
        } else {
            $data = ['data' => DaftarPesertaInterviewResource::collection($dataQuery->get())];
        }

        return response()->json($data);
    }

    public function penilaianInterview($pendaftar_id)
    {
        // Ambil hanya satu pendaftar berdasarkan ID
        $pendaftar = Pendaftar::with([
            'peserta.subInstitusi.institusi',
            'peserta.user.identitas',
            'seleksi',
            'wawancara' => function ($query) use ($pendaftar_id) {
                $query->where('pendaftar_id', $pendaftar_id);
            },
        ])
            ->where('id', $pendaftar_id)  // filter berdasarkan pendaftar_id
            ->first();

        // Pastikan pendaftar ditemukan
        if (!$pendaftar) {
            return response()->json(['message' => 'Pendaftar tidak ditemukan'], 404);
        }
        $seleksi_id = $pendaftar->seleksi_id;

        $topikInterviews = TopikInterview::with([
            'bankSoal.kategori',
            'nilaiInterview.wawancara' => function ($query) use ($pendaftar_id) {
                $query->where('pendaftar_id', $pendaftar_id);
            }
        ])
            ->where('seleksi_id', $seleksi_id)  // Filter berdasarkan seleksi_id
            ->get();


        $data = ['pendaftar' => new PendaftarResource($pendaftar), 'topik_interviews' => SoalWawancaraResource::collection($topikInterviews)];

        return response()->json($data);
    }

    public function store(InterviewerRequest $request)
    {
        try {
            DB::beginTransaction();
            $interviewer = Interviewer::create($request->all());
            DB::commit();
            return response()->json(['message' => 'data baru berhasil dibuat', 'data' => $interviewer], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function show(Interviewer $interviewer)
    {
        return response()->json(['data' => $interviewer], 200);
    }

    public function update(InterviewerRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $interviewer = Interviewer::findOrFail($id);
            $interviewer->update($request->all());
            DB::commit();
            return response()->json(['message' => 'berhasil diperbarui', 'data' => $interviewer], 200);
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
            $interviewer = Interviewer::findOrFail($id);
            $interviewer->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat menghapus : ' . $e->getMessage()], 500);
        }
    }
}
