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
        $statusDaftar = $this->checkStatus($this->daftar_mulai, $this->daftar_selesai);
        $statusVerifikasi = $this->checkStatus($this->verifikasi_mulai, $this->verifikasi_selesai);

        return [
            'id' => $this->id,
            'nama' => $this->nama,
            'tahun' => $this->tahun,
            'keterangan' => ($this->keterangan) ? $this->keterangan : "",
            'user' => $this->user->name,
            'jenis' => $this->jenis->nama,
            'daftar_mulai' => $this->daftar_mulai,
            'daftar_selesai' => $this->daftar_selesai,
            'daftar_status' => $statusDaftar,
            'verifikasi_mulai' => $this->verifikasi_mulai,
            'verifikasi_selesai' => $this->verifikasi_selesai,
            'verifikasi_status' => $statusVerifikasi,
            'now' => now(),
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->updated_at->format('Y-m-d'),
        ];
    }

    private function checkStatus($start, $end)
    {
        $today = date('Y-m-d'); // Tanggal hari ini dalam format Y-m-d

        // Ubah format tanggal mulai dan selesai ke Y-m-d
        $startDate = date('Y-m-d', strtotime($start));
        $endDate = date('Y-m-d', strtotime($end));

        return ($today >= $startDate && $today <= $endDate);
    }
}
