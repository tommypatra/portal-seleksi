<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PemeriksaSyaratResource extends JsonResource
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
            'pendaftar_id' => $this->pendaftar_id,
            'role_seleksi_id' => $this->roleSeleksi->id,
            'role_user_id' => $this->roleSeleksi->role_user_id,
            'peserta_id' => $this->pendaftar->peserta->id,
            'peserta_noid' => $this->pendaftar->peserta->noid,
            'user_id' => $this->pendaftar->peserta->user->id,
            'user_name' => $this->pendaftar->peserta->user->name,
            'user_email' => $this->pendaftar->peserta->user->email,
            'role_user_name' => $this->roleSeleksi->roleUser->user->name,
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->updated_at->format('Y-m-d'),
        ];
    }
}
