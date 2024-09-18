<?php

namespace App\Http\Controllers;

use App\Http\Requests\BankSoalRequest;
use App\Http\Resources\BankSoalResource;
use App\Models\BankSoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankSoalController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = BankSoal::with(['kategori', 'topikInterview'])->orderBy('soal', 'asc');

        // agar blok where has tidak mengganggu query lain
        // if ($request->filled('search')) {
        //     $cari = $request->search;
        //     $dataQuery->where(function ($query) use ($cari) {
        //         $query->where('soal', 'like', '%' . $cari . '%')
        //             ->orWhereHas('kategori', function ($query) use ($cari) {
        //                 $query->where('nama', 'like', '%' . $cari . '%');
        //             });
        //     });
        // }

        if ($request->filled('seleksi_id')) {
            $seleksi_id = $request->seleksi_id;
            $dataQuery->whereDoesntHave('topikInterview', function ($query) use ($seleksi_id) {
                $query->where('seleksi_id', $seleksi_id);
            });
        }

        if ($request->filled('search')) {
            $cari = $request->search;
            $dataQuery->where(function ($query) use ($cari) {
                $query->where('soal', 'like', '%' . $cari . '%')
                    ->orWhereHas('kategori', function ($query) use ($cari) {
                        $query->where('nama', 'like', '%' . $cari . '%');
                    });
            });
        }

        $limit = $request->filled('limit') ? $request->limit : 0;
        if ($limit) {
            $data = $dataQuery->paginate($limit);
            $resourceCollection = $data->map(function ($item) {
                return new BankSoalResource($item);
            });
            $data->setCollection($resourceCollection);
        } else {
            $data = ['data' => BankSoalResource::collection($dataQuery->get())];
        }

        return response()->json($data);
    }

    public function store(BankSoalRequest $request)
    {
        try {
            DB::beginTransaction();
            $bank_soal = BankSoal::create($request->all());
            DB::commit();
            return response()->json(['message' => 'data baru berhasil dibuat', 'data' => new BankSoalResource($bank_soal)], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function show(BankSoal $bank_soal)
    {
        return response()->json(['data' => new BankSoalResource($bank_soal)], 200);
    }

    public function update(BankSoalRequest $request, BankSoal $bank_soal)
    {
        try {
            DB::beginTransaction();
            $bank_soal->update($request->all());
            DB::commit();
            return response()->json(['message' => 'berhasil diperbarui', 'data' => new BankSoalResource($bank_soal)], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }

    public function destroy(BankSoal $bank_soal)
    {
        try {
            DB::beginTransaction();
            $bank_soal->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }
}
