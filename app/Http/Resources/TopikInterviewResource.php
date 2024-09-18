<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TopikInterviewResource extends JsonResource
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
            // 'bank_soal' => new BankSoalResource($this->bankSoal),
            'bank_soal_id' => $this->bankSoal->id,
            'kategori_nama' => $this->bankSoal->kategori->nama,
            'kategori_id' => $this->bankSoal->kategori->id,
            'kategori_keterangan' => $this->bankSoal->kategori->keterangan,
            'bank_soal' => $this->bankSoal->soal,
            'seleksi_id' => $this->seleksi->id,
            'seleksi_nama' => $this->seleksi->nama,
            'seleksi_tahun' => $this->seleksi->tahun,
            'keterangan' => $this->keterangan,
            'bobot' => $this->bobot,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
