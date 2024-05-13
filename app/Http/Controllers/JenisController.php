<?php

namespace App\Http\Controllers;

use App\Http\Requests\JenisRequest;
use App\Http\Resources\JenisResource;
use App\Models\Jenis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JenisController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = Jenis::orderBy('nama', 'asc');
        if ($request->filled('search')) {
            $dataQuery->where('nama', 'like', '%' . $request->search . '%');
        }

        $limit = $request->filled('limit') ? $request->limit : 0;
        if ($limit) {
            $data = $dataQuery->paginate($limit);
            $resourceCollection = $data->map(function ($item) {
                return new JenisResource($item);
            });
            $data->setCollection($resourceCollection);
        } else {
            $data = ['data' => JenisResource::collection($dataQuery->get())];
        }
        return response()->json($data);
    }

    public function store(JenisRequest $request)
    {
        try {
            DB::beginTransaction();
            $jenis = Jenis::create($request->all());
            DB::commit();
            return response()->json(['message' => 'data baru berhasil dibuat', 'data' => $jenis], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function show(Jenis $jenis)
    {
        return response()->json(['data' => $jenis], 200);
    }

    public function update(JenisRequest $request, Jenis $jenis)
    {
        try {
            DB::beginTransaction();
            $jenis->update($request->all());
            DB::commit();
            return response()->json(['message' => 'berhasil diperbarui', 'data' => $jenis], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Jenis $jenis)
    {
        try {
            DB::beginTransaction();
            $jenis->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }
}
