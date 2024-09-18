<?php

namespace App\Http\Resources;

use App\Http\Resources\HasilWawancaraResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DaftarPesertaInterviewResource extends JsonResource
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
            'peserta_id' => $this->peserta_id,
            'peserta_user_id' => $this->peserta->user->id,
            'peserta_user_name' => $this->peserta->user->name,
            'peserta_user_email' => $this->peserta->user->email,
            'noid' => $this->peserta->noid,
            'institusi_id' => $this->peserta->subInstitusi->institusi->id,
            'institusi_nama' => $this->peserta->subInstitusi->institusi->nama,
            'institusi_is_negeri' => $this->peserta->subInstitusi->institusi->is_negeri,
            'sub_institusi_id' => $this->peserta->subInstitusi->id,
            'sub_institusi_nama' => $this->peserta->subInstitusi->nama,
            'sub_institusi_jenis' => $this->peserta->subInstitusi->jenis,
            'tahun' => $this->tahun,
            // default wawancara
            // 'wawancara' => $this->wawancara,
            //jika respon 1 data dan diatur dalam resource
            // 'wawancara' => new HasilWawancaraResource($this->wawancara),
            //jika respon lebih 1 data dan diatur dalam resource
            'wawancara' => HasilWawancaraResource::collection($this->wawancara),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
