<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HasilWawancaraResource extends JsonResource
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
            'is_lulus' => $this->is_lulus,
            'nilai' => $this->nilai,
            // 'nilai_interview' => $this->nilai_interview,
            'interviewer_user_id' => $this->roleSeleksi->roleUser->user->id,
            'interviewer_user_name' => $this->roleSeleksi->roleUser->user->name,
            'interviewer_user_email' => $this->roleSeleksi->roleUser->user->email,
            'role_user_id' => $this->roleSeleksi->roleUser->id,
            'role_seleksi_id' => $this->roleSeleksi->id,
            'seleksi_id' => $this->roleSeleksi->seleksi_id,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
