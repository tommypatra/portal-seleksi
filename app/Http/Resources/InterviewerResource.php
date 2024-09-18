<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InterviewerResource extends JsonResource
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
        $statusWawancara = cekStatusJadwal($this->wawancara_mulai, $this->wawancara_selesai);

        $blmPeriksa = 0;
        $ms = 0;
        $tms = 0;
        $jumlahPendaftar = count($this->pendaftar);
        $persen = 0;

        if ($jumlahPendaftar > 0) {
            foreach ($this->pendaftar as $i => $dp) {
                if ($dp->interviewer_lulus === null) {
                    $blmPeriksa++;
                } elseif ($dp->interviewer_lulus === 0) {
                    $ms++;
                } elseif ($dp->interviewer_lulus === 1) {
                    $tms++;
                }
            }
            $persen = (($ms + $tms) / $jumlahPendaftar) * 100;
            $persen = round($persen);
        }
        return [
            'id' => $this->id,
            'nama' => $this->nama,
            'tahun' => $this->tahun,
            'keterangan' => ($this->keterangan) ? $this->keterangan : "",
            'daftar_mulai' => $this->daftar_mulai,
            'daftar_selesai' => $this->daftar_selesai,
            'daftar_status' => $statusDaftar,
            'wawancara_mulai' => $this->wawancara_mulai,
            'wawancara_selesai' => $this->wawancara_selesai,
            'wawancara_status' => $statusWawancara,
            'now' => now(),
            'jumlah_pendaftar' => $jumlahPendaftar,
            'tms' => $tms,
            'ms' => $ms,
            'persen' => $persen,
            'blm_periksa' => $blmPeriksa,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
