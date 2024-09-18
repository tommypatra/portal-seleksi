<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SoalWawancaraResource extends JsonResource
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
            'bank_soal_id' => $this->bankSoal->id,
            'bank_soal' => $this->bankSoal->soal,
            'kategori_soal_id' => $this->bankSoal->kategori->id,
            'kategori_soal' => $this->bankSoal->kategori->nama,
            'bobot' => $this->bobot,
            'keterangan' => $this->keterangan,
            'seleksi_id' => $this->seleksi_id,
            'nilai_interview_id' => isset($this->nilaiInterview[0]->id) ? $this->nilaiInterview[0]->id : null,
            'nilai_interview' => isset($this->nilaiInterview[0]->id) ? $this->nilaiInterview[0]->nilai : null,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
