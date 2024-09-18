<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WawancaraResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'is_lulus' => $this->is_lulus,
            // 'nilai_interview' => $this->nilai_interview,
            'user_id' => $this->pendaftar->peserta->user->id,
            'peserta_id' => $this->pendaftar->peserta->id,
            'pendaftar_id' => $this->pendaftar->id,
            'user_name' => $this->pendaftar->peserta->user->name,
            'email' => $this->pendaftar->peserta->user->email,
            'peserta_noid' => $this->pendaftar->peserta->noid,
            'tahun' => $this->pendaftar->tahun,
            'now' => now(),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
