<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SeleksiResource extends JsonResource
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
        $statusDaftar = cekStatusJadwal($this->daftar_mulai, $this->daftar_selesai);
        $statusVerifikasi = cekStatusJadwal($this->verifikasi_mulai, $this->verifikasi_selesai);

        return [
            'id' => $this->id,
            'nama' => $this->nama,
            'tahun' => $this->tahun,
            'pendaftar' => ($this->pendaftar) ? count($this->pendaftar) : 0,
            'keterangan' => ($this->keterangan) ? $this->keterangan : "",
            'user' => $this->user->name,
            'jenis' => $this->jenis->nama,
            'is_publish' => $this->is_publish,
            'is_publish_label' => ($this->is_publish == 1) ? "Terpublikasi" : "Pending",
            'daftar_mulai' => $this->daftar_mulai,
            'daftar_selesai' => $this->daftar_selesai,
            'daftar_status' => $statusDaftar,
            'verifikasi_mulai' => $this->verifikasi_mulai,
            'verifikasi_selesai' => $this->verifikasi_selesai,
            'verifikasi_status' => $statusVerifikasi,
            'now' => now(),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
