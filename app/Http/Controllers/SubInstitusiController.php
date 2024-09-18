<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubInstitusiRequest;
use App\Http\Resources\SubInstitusiResource;
use App\Models\SubInstitusi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubInstitusiController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = SubInstitusi::with(['institusi'])->orderBy('nama', 'asc');
        if ($request->filled('institusi_id')) {
            $dataQuery->where('institusi_id', $request->institusi_id);
        }

        if ($request->filled('search')) {
            $dataQuery->where('nama', 'like', '%' . $request->search . '%');
        }

        $limit = $request->filled('limit') ? $request->limit : 0;
        if ($limit) {
            $data = $dataQuery->paginate($limit);
            $resourceCollection = $data->map(function ($item) {
                return new SubInstitusiResource($item);
            });
            $data->setCollection($resourceCollection);
        } else {
            $data = ['data' => SubInstitusiResource::collection($dataQuery->get())];
        }

        return response()->json($data);
    }

    public function store(SubInstitusiRequest $request)
    {
        try {
            DB::beginTransaction();
            $sub_institusi = SubInstitusi::create($request->all());
            DB::commit();
            return response()->json(['message' => 'data baru berhasil dibuat', 'data' => new SubInstitusiResource($sub_institusi)], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function show(SubInstitusi $sub_institusi)
    {
        return response()->json(['data' => new SubInstitusiResource($sub_institusi)], 200);
    }

    public function update(SubInstitusiRequest $request, SubInstitusi $sub_institusi)
    {
        try {
            DB::beginTransaction();
            $sub_institusi->update($request->all());
            DB::commit();
            return response()->json(['message' => 'berhasil diperbarui', 'data' => new SubInstitusiResource($sub_institusi)], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }

    public function destroy(SubInstitusi $sub_institusi)
    {
        try {
            // dd($sub_institusi);
            DB::beginTransaction();
            $sub_institusi->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }
}
