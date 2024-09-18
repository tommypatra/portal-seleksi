<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleSeleksiRequest;
use App\Http\Resources\RoleSeleksiResource;
use App\Models\RoleSeleksi;
use App\Models\RoleUser;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleSeleksiController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = RoleSeleksi::with(['roleUser.role', 'roleUser.user', 'seleksi', 'wawancara.pendaftar.peserta.user', 'pemeriksaSyarat.pendaftar.peserta.user'])->orderBy('id', 'desc');
        if ($request->filled('seleksi_id')) {
            $dataQuery->where('seleksi_id', $request->input('seleksi_id'));
        }

        if ($request->filled('role')) {
            $role = $request->input('role');
            $dataQuery->whereHas('roleUser.role', function ($query) use ($role) {
                $query->where('nama', $role);
            });
        }

        // if ($request->filled('search')) {
        //     $search = $request->input('search');
        //     $dataQuery->whereHas('roleUser.user', function ($query) use ($search) {
        //         $query->where('name', 'LIKE', '%' . $search . '%');
        //     });
        // }

        // if (!$request->filled('is_web')) {
        //     $dataQuery->where('user_id', auth()->id());
        // }

        $limit = $request->filled('limit') ? $request->limit : 0;
        if ($limit) {
            $data = $dataQuery->paginate($limit);
            $resourceCollection = $data->map(function ($item) {
                return new RoleSeleksiResource($item);
            });
            $data->setCollection($resourceCollection);
        } else {
            $data = ['data' => RoleSeleksiResource::collection($dataQuery->get())];
        }

        return response()->json($data);
    }

    public function store(RoleSeleksiRequest $request)
    {
        try {
            DB::beginTransaction();
            $roleSeleksi = RoleSeleksi::create($request->all());
            DB::commit();
            return response()->json(['message' => 'data baru berhasil dibuat', 'data' => $roleSeleksi], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function show(RoleSeleksi $roleSeleksi)
    {
        return response()->json(['data' => $roleSeleksi], 200);
    }

    public function update(RoleSeleksiRequest $request, RoleSeleksi $roleSeleksi)
    {
        try {
            DB::beginTransaction();
            $roleSeleksi->update($request->all());
            DB::commit();
            return response()->json(['message' => 'berhasil diperbarui', 'data' => $roleSeleksi], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }

    public function destroy(RoleSeleksi $roleSeleksi)
    {
        try {
            DB::beginTransaction();
            $roleSeleksi->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }

    public function roleTersedia($seleksi_id, $role)
    {
        $dataQuery = RoleUser::with(['user', 'role', 'RoleSeleksi'])
            ->whereHas('role', function ($query) use ($role) {
                $query->where('nama', strtolower($role));
            })
            ->whereDoesntHave('RoleSeleksi', function ($query) use ($seleksi_id) {
                $query->where('seleksi_id', $seleksi_id);
            })
            ->get();
        return response()->json(['data' => $dataQuery]);
    }
}
