<?php

namespace App\Http\Controllers;

use App\Models\SeleksiAsal;
use App\Models\SubInstitusi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\SeleksiAsalRequest;
use App\Http\Resources\SeleksiAsalResource;

class SeleksiAsalController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = SeleksiAsal::with(['seleksi.jenis', 'subinstitusi.institusi'])->orderBy('id', 'asc');
        if ($request->filled('seleksi_id')) {
            $dataQuery->where('seleksi_id', $request->seleksi_id);
        }

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $dataQuery->where(function ($query) use ($searchTerm) {
                $query->whereHas('subinstitusi', function ($query) use ($searchTerm) {
                    $query->where('nama', 'like', $searchTerm);
                })->orWhereHas('subinstitusi.institusi', function ($query) use ($searchTerm) {
                    $query->where('nama', 'like', $searchTerm);
                });
            });
        }

        $limit = $request->filled('limit') ? $request->limit : 0;
        if ($limit) {
            $data = $dataQuery->paginate($limit);
            $resourceCollection = $data->map(function ($item) {
                return new SeleksiAsalResource($item);
            });
            $data->setCollection($resourceCollection);
        } else {
            $data = ['data' => SeleksiAsalResource::collection($dataQuery->get())];
        }

        return response()->json($data);
    }

    public function store(SeleksiAsalRequest $request)
    {
        try {
            DB::beginTransaction();
            $seleksi_asal = SeleksiAsal::create($request->all());
            DB::commit();
            return response()->json(['message' => 'data baru berhasil dibuat', 'data' => new SeleksiAsalResource($seleksi_asal)], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function show(SeleksiAsal $seleksi_asal)
    {
        return response()->json(['data' => new SeleksiAsalResource($seleksi_asal)], 200);
    }

    public function update(SeleksiAsalRequest $request, SeleksiAsal $seleksi_asal)
    {
        try {
            DB::beginTransaction();
            $seleksi_asal->update($request->all());
            DB::commit();
            return response()->json(['message' => 'berhasil diperbarui', 'data' => new SeleksiAsalResource($seleksi_asal)], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }

    public function destroy(SeleksiAsal $seleksi_asal)
    {
        try {
            // dd($seleksi_asal);
            DB::beginTransaction();
            $seleksi_asal->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }

    public function subInstitusiTersedia($seleksi_id)
    {
        $subInstitusi = SubInstitusi::with(['institusi', 'seleksiAsal'])
            ->whereDoesntHave('seleksiAsal', function ($query) use ($seleksi_id) {
                $query->where('seleksi_id', $seleksi_id);
            })
            ->get();
        return response()->json(['data' => $subInstitusi]);
    }
}
