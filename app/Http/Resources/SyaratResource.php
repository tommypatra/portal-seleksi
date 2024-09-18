<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SyaratResource extends JsonResource
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
            'nama' => $this->nama,
            'keterangan' => ($this->keterangan) ? $this->keterangan : "",
            'seleksi' => $this->seleksi,
            'jenis' => $this->jenis,
            'jenis_label' => ($this->jenis === 'img') ? 'Gambar' : 'PDF',
            'is_wajib' => $this->is_wajib,
            'is_wajib_label' => ($this->is_wajib) ? 'Wajib' : 'Tidak Wajib',
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
