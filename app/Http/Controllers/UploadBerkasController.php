<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadBerkasRequest;
use App\Models\UploadBerkas;
use App\Models\Pendaftar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File as FileFacade;

class UploadBerkasController extends Controller
{

    public function jadwalSeleksi(Request $request)
    {
        // $pendaftar_id=($request->filled('pendaftar_id'))?$request->input('pendaftar_id')
        $dataQuery = uploadBerkas::with([
            'syarat' => function ($query) {
                $query->orderBy('is_wajib', 'desc')->orderBy('nama', 'asc');
            }
        ]);

        $limit = $request->filled('limit') ? $request->limit : 0;
        if ($limit) {
            $data = $dataQuery->paginate($limit);
            $resourceCollection = $data->map(function ($item) {
                return new UploadBerkasResource($item);
            });
            $data->setCollection($resourceCollection);
        } else {
            $data = ['data' => UploadBerkasResource::collection($dataQuery->get())];
        }

        return response()->json($data);
    }


    public function store(UploadBerkasRequest $request)
    {
        try {
            DB::beginTransaction();
            //verifikasi sekaligus status pendaftaran
            $pendaftar = Pendaftar::find($request->pendaftar_id)->first();
            if (!statusJadwal($pendaftar->seleksi_id)->statusDaftar) {
                return response()->json(['message' => 'pendaftaran sudah tertutup'], 500);
            }

            $storagePath = 'syarat/' . date('Y') . '/' . date('m');
            $uploadProses = uploadFile($request, 'file', $storagePath);
            if ($uploadProses) {
                $request['path'] = $uploadProses['path'];
            } else {
                return response()->json(['message' => $uploadProses], 500);
            }
            $data = UploadBerkas::create($request->all());
            DB::commit();
            return response()->json(['message' => 'data baru berhasil dibuat', 'data' => $data], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function update(UploadBerkasRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $uploadberkas = UploadBerkas::findOrFail($id);
            $uploadberkas->update($request->all());
            DB::commit();
            return response()->json(['message' => 'berhasil diperbarui', 'data' => $verfikator], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            //verifikasi sekaligus status pendaftaran
            $uploadBerkas = UploadBerkas::with(['pendaftar'])->find($id);
            if (!statusJadwal($uploadBerkas->pendaftar->seleksi_id)->statusDaftar) {
                return response()->json(['message' => 'pendaftaran sudah tertutup'], 500);
            }

            if (!empty($uploadBerkas->path) && FileFacade::exists($uploadBerkas->path)) {
                FileFacade::delete($uploadBerkas->path);
            }

            $uploadBerkas->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat mengapus : ' . $e->getMessage()], 500);
        }
    }
}
