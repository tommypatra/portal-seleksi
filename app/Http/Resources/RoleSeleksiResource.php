<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class RoleSeleksiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'seleksi_id' => $this->seleksi_id,
            'seleksi_nama' => $this->seleksi->nama,
            'seleksi_tahun' => $this->seleksi->tahun,
            'seleksi_keterangan' => $this->seleksi->tahun,
            'user_id' => $this->roleUser->user_id,
            'user_nama' => $this->roleUser->user->name,
            'user_email' => $this->roleUser->user->email,
            'role_user_id' => $this->roleUser->id,
            'grup_id' => $this->roleUser->role->id,
            'grup_nama' => $this->roleUser->role->nama,
            'jumlah_peserta' => count($this->pemeriksaSyarat),
            'list_peserta' => $this->pemeriksaSyarat,

            'jumlah_wawancara' => count($this->wawancara),
            'list_wawancara' => $this->wawancara,


            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
        return parent::toArray($request);
    }
}
