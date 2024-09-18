<?php

namespace App\Http\Controllers;

use App\Http\Requests\SyaratRequest;
use App\Http\Resources\SyaratResource;
use App\Models\Syarat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SyaratController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = Syarat::with(['seleksi'])->orderBy('nama', 'asc');
        if ($request->filled('search')) {
            $dataQuery->where('nama', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('seleksi_id')) {
            $seleksi_id = $request->input('seleksi_id');
            $dataQuery->whereHas('seleksi', function ($query) use ($seleksi_id) {
                $query->where('id', $seleksi_id);
            });
        }

        $limit = $request->filled('limit') ? $request->limit : 0;
        if ($limit) {
            $data = $dataQuery->paginate($limit);
            $resourceCollection = $data->map(function ($item) {
                return new SyaratResource($item);
            });
            $data->setCollection($resourceCollection);
        } else {
            $data = ['data' => SyaratResource::collection($dataQuery->get())];
        }

        return response()->json($data);
    }

    public function store(SyaratRequest $request)
    {
        try {
            DB::beginTransaction();
            $syarat = Syarat::create($request->all());
            DB::commit();
            return response()->json(['message' => 'data baru berhasil dibuat', 'data' => new SyaratResource($syarat)], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function show(Syarat $syarat)
    {
        return response()->json(['data' => new SyaratResource($syarat)], 200);
    }

    public function update(SyaratRequest $request, Syarat $syarat)
    {
        try {
            DB::beginTransaction();
            $syarat->update($request->all());
            DB::commit();
            return response()->json(['message' => 'berhasil diperbarui', 'data' => new SyaratResource($syarat)], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Syarat $syarat)
    {
        try {
            DB::beginTransaction();
            $syarat->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }
}
