<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VerifPesertaResource extends JsonResource
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
        $statusVerifikasi = cekStatusJadwal($this->seleksi->verifikasi_mulai, $this->seleksi->verifikasi_selesai);
        $syarat = $this->seleksi->syarat;
        $upload_berkas = $this->uploadBerkas;
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
            'pendaftar_id' => $this->id,
            'pendaftar_verifikasi_keterangan' => $this->verifikasi_keterangan,
            'pendaftar_verifikasi_lulus' => $this->verifikasi_lulus,
            'syarat' => $syarat,
            'seleksi_id' => $this->seleksi->id,
            'seleksi_nama' => $this->seleksi->nama,
            'seleksi_tahun' => $this->seleksi->tahun,
            'verifikasi_mulai' => $this->seleksi->verifikasi_mulai,
            'verifikasi_selesai' => $this->seleksi->verifikasi_selesai,
            'verifikasi_status' => $statusVerifikasi,
            'peserta_id' => $this->peserta->id,
            'peserta_noid' => $this->peserta->noid,
            'user_id' => $this->peserta->user->id,
            'user_name' => $this->peserta->user->name,
            'user_email' => $this->peserta->user->email,
            'subinstitusi_nama' => $this->peserta->subInstitusi->nama,
            'subinstitusi_jenis' => $this->peserta->subInstitusi->jenis,
            'subinstitusi_keterangan' => $this->peserta->subInstitusi->keterangan,
            'institusi_nama' => $this->peserta->subInstitusi->institusi->nama,
            'institusi_is_negeri' => $this->peserta->subInstitusi->institusi->is_negeri,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
