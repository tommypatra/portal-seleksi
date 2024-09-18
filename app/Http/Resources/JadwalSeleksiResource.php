<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JadwalSeleksiResource extends JsonResource
{
    public function toArray($request)
    {
        // return parent::toArray($request);
        $statusDaftar = $this->checkStatus($this->daftar_mulai, $this->daftar_selesai);
        $statusVerifikasi = $this->checkStatus($this->verifikasi_mulai, $this->verifikasi_selesai);

        $syarat = $this->syarat;
        $upload_berkas = (count($this->pendaftar) > 0) ? $this->pendaftar[0]->uploadBerkas : [];
        foreach ($syarat as $i => $syr) {
            $syarat[$i]->upload = null;
            foreach ($upload_berkas as $j => $upl) {
                if ($syr->id === $upl->syarat_id) {
                    $syarat[$i]->upload = $upl;
                    break;
                }
            }
        }

        return [
            'id' => $this->id,
            'nama' => $this->nama,
            'tahun' => $this->tahun,
            'pendaftar' => $this->pendaftar,
            'keterangan' => ($this->keterangan) ? $this->keterangan : "",
            'user' => $this->user->name,
            'jenis' => $this->jenis->nama,
            'syarat' => $syarat,
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

    private function checkStatus($start, $end)
    {
        $today = date('Y-m-d'); // Tanggal hari ini dalam format Y-m-d

        // Ubah format tanggal mulai dan selesai ke Y-m-d
        $startDate = date('Y-m-d', strtotime($start));
        $endDate = date('Y-m-d', strtotime($end));

        return ($today >= $startDate && $today <= $endDate);
    }
}
