<?php

namespace App\Http\Controllers;

use App\Http\Requests\AkunRequest;
use App\Http\Resources\AkunResource;
use App\Models\RoleUser;
use App\Models\Peserta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AkunController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = User::with([
            'roleUser' => function ($query) {
                $query->orderBy('role_id', 'asc')->orderBy('id', 'asc');
            },
            'roleUser.role',
            'peserta' => function ($query) {
                $query->orderBy('noid', 'asc');
            }
        ])->orderBy('name', 'asc');
        if ($request->filled('search')) {
            $dataQuery->where('name', 'like', '%' . $request->search . '%');
        }

        $limit = $request->filled('limit') ? $request->limit : 0;
        if ($limit) {
            $data = $dataQuery->paginate($limit);
            $resourceCollection = $data->map(function ($item) {
                return new AkunResource($item);
            });
            $data->setCollection($resourceCollection);
        } else {
            $data = ['data' => AkunResource::collection($dataQuery->get())];
        }

        return response()->json($data);
    }

    public function store(AkunRequest $request)
    {
        try {
            DB::beginTransaction();
            $akun = Admin::create($request->all());
            DB::commit();
            return response()->json(['message' => 'data baru berhasil dibuat', 'data' => $akun], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function hakAkses(Request $request, $user_id)
    {
        $validatedData = $request->validate([
            'cekakses.*' => 'required|integer',
        ], [
            'cekakses.*.required' => 'Pilih salah satu akses',
            'cekakses.*.integer' => 'Nilai akses harus berupa angka',
        ]);

        try {
            DB::beginTransaction();
            $akun = [];
            foreach ($request->cekakses as $role_id) {
                $data = [
                    'user_id' => $user_id,
                    'role_id' => $role_id,
                ];
                $akun[] = RoleUser::create($data);
            }
            DB::commit();
            return response()->json(['message' => 'data baru berhasil dibuat', 'data' => $akun], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json(['data' => $user], 200);
    }

    public function update(AkunRequest $request, Admin $akun)
    {
        try {
            DB::beginTransaction();
            $akun->update($request->all());
            DB::commit();
            return response()->json(['message' => 'berhasil diperbarui', 'data' => $akun], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Admin $akun)
    {
        try {
            DB::beginTransaction();
            $akun->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }

    public function destroyAkses($id)
    {
        try {
            DB::beginTransaction();
            $dataDB = RoleUser::findOrFail($id);
            $dataDB->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }

    public function akunVerifikator()
    {
        $dataQuery = User::with(['roleUser.role'])
            ->whereHas('roleUser.role', function ($query) {
                $query->where('nama', 'Verifikator');
            })
            ->get();
        return response()->json(['data' => $dataQuery]);
    }

    // public function destroyVerifikator($id)
    // {
    //     try {
    //         DB::beginTransaction();
    //         $dataDB = Verifikator::findOrFail($id);
    //         $dataDB->delete();
    //         DB::commit();
    //         return response()->json(null, 204);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
    //     }
    // }

    // public function destroyPeserta($id)
    // {
    //     try {
    //         DB::beginTransaction();
    //         $dataDB = Peserta::findOrFail($id);
    //         $dataDB->delete();
    //         DB::commit();
    //         return response()->json(null, 204);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
    //     }
    // }
}
