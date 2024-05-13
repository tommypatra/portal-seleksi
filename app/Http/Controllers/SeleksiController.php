<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeleksiRequest;
use App\Http\Resources\SeleksiResource;
use App\Models\Seleksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SeleksiController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = Seleksi::with(['jenis', 'user'])->orderBy('nama', 'asc');
        if ($request->filled('search')) {
            $dataQuery->where('nama', 'like', '%' . $request->search . '%');
        }

        if (!$request->filled('is_web')) {
            // if (!is_admin() && !is_editor())
            $dataQuery->where('user_id', auth()->id());
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
