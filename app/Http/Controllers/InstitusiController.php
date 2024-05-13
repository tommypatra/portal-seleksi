<?php

namespace App\Http\Controllers;

use App\Http\Requests\InstitusiRequest;
use App\Http\Resources\InstitusiResource;
use App\Models\Institusi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstitusiController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = Institusi::orderBy('nama', 'asc');
        if ($request->filled('search')) {
            $dataQuery->where('nama', 'like', '%' . $request->search . '%');
        }

        $limit = $request->filled('limit') ? $request->limit : 0;
        if ($limit) {
            $data = $dataQuery->paginate($limit);
            $resourceCollection = $data->map(function ($item) {
                return new InstitusiResource($item);
            });
            $data->setCollection($resourceCollection);
        } else {
            $data = ['data' => InstitusiResource::collection($dataQuery->get())];
        }

        return response()->json($data);
    }

    public function store(InstitusiRequest $request)
    {
        try {
            DB::beginTransaction();
            $institusi = Institusi::create($request->all());
            DB::commit();
            return response()->json(['message' => 'data baru berhasil dibuat', 'data' => new InstitusiResource($institusi)], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function show(Institusi $institusi)
    {
        return response()->json(['data' => new InstitusiResource($institusi)], 200);
    }

    public function update(InstitusiRequest $request, Institusi $institusi)
    {
        try {
            DB::beginTransaction();
            $institusi->update($request->all());
            DB::commit();
            return response()->json(['message' => 'berhasil diperbarui', 'data' => new InstitusiResource($institusi)], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Institusi $institusi)
    {
        try {
            DB::beginTransaction();
            $institusi->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }
}
