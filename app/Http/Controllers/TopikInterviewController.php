<?php

namespace App\Http\Controllers;

use App\Http\Requests\TopikInterviewRequest;
use App\Http\Resources\TopikInterviewResource;
use App\Models\TopikInterview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TopikInterviewController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = TopikInterview::with(['seleksi', 'nilaiInterview', 'bankSoal.kategori'])
            ->orderBy('seleksi_id', 'asc')
            ->orderBy('bank_soal_id', 'asc');
        if ($request->filled('search')) {
            $search = $request->input('search');
            $dataQuery->whereHas('bankSoal', function ($query) use ($search) {
                where('soal', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('seleksi_id')) {
            $seleksi_id = $request->input('seleksi_id');
            $dataQuery->where('seleksi_id', $seleksi_id);
        }

        $limit = $request->filled('limit') ? $request->limit : 0;
        if ($limit) {
            $data = $dataQuery->paginate($limit);
            $resourceCollection = $data->map(function ($item) {
                return new TopikInterviewResource($item);
            });
            $data->setCollection($resourceCollection);
        } else {
            $data = ['data' => TopikInterviewResource::collection($dataQuery->get())];
        }

        return response()->json($data);
    }

    public function store(TopikInterviewRequest $request)
    {
        try {
            DB::beginTransaction();
            $topikInterview = TopikInterview::create($request->all());
            DB::commit();
            return response()->json(['message' => 'data baru berhasil dibuat', 'data' => new TopikInterviewResource($topikInterview)], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $dataQuery = TopikInterview::with(['seleksi', 'nilaiInterview', 'bankSoal.kategori'])
            ->where('id', $id)->first();
        return response()->json(['data' => new TopikInterviewResource($dataQuery)], 200);
    }

    public function update(TopikInterviewRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            // Mencari data berdasarkan ID, jika tidak ditemukan akan throw 404
            $topikInterview = TopikInterview::findOrFail($id);

            // Melakukan update dengan data dari request
            $topikInterview->update($request->all());
            DB::commit();
            return response()->json(['message' => 'berhasil diperbarui', 'data' => new TopikInterviewResource($topikInterview)], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            // Mencari data berdasarkan ID, jika tidak ditemukan akan throw 404
            $topikInterview = TopikInterview::findOrFail($id);

            // Melakukan update dengan data dari request
            $topikInterview->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }
}
