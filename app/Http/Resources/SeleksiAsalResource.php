<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SeleksiAsalResource extends JsonResource
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
            'seleksi_jenis' => $this->seleksi->jenis->nama,
            'seleksi_keterangan' => ($this->seleksi->keterangan) ? $this->seleksi->keterangan : "",
            'sub_institusi_id' => $this->sub_institusi_id,
            'sub_institusi_nama' => $this->subinstitusi->nama,
            'institusi_id' => $this->subinstitusi->institusi_id,
            'institusi_nama' => $this->subinstitusi->institusi->nama,
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->updated_at->format('Y-m-d'),
        ];
    }
}
