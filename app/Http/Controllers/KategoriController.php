<?php

namespace App\Http\Controllers;

use App\Http\Requests\KategoriRequest;
use App\Http\Resources\KategoriResource;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = Kategori::orderBy('nama', 'asc');
        if ($request->filled('search')) {
            $dataQuery->where('nama', 'like', '%' . $request->search . '%');
        }

        $limit = $request->filled('limit') ? $request->limit : 0;
        if ($limit) {
            $data = $dataQuery->paginate($limit);
            $resourceCollection = $data->map(function ($item) {
                return new KategoriResource($item);
            });
            $data->setCollection($resourceCollection);
        } else {
            $data = ['data' => KategoriResource::collection($dataQuery->get())];
        }

        return response()->json($data);
    }

    public function store(KategoriRequest $request)
    {
        try {
            DB::beginTransaction();
            $kategori = Kategori::create($request->all());
            DB::commit();
            return response()->json(['message' => 'data baru berhasil dibuat', 'data' => new KategoriResource($kategori)], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function show(Kategori $kategori)
    {
        return response()->json(['data' => new KategoriResource($kategori)], 200);
    }

    public function update(KategoriRequest $request, Kategori $kategori)
    {
        try {
            DB::beginTransaction();
            $kategori->update($request->all());
            DB::commit();
            return response()->json(['message' => 'berhasil diperbarui', 'data' => new KategoriResource($kategori)], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Kategori $kategori)
    {
        try {
            DB::beginTransaction();
            $kategori->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }
}
