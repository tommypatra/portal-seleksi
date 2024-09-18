<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PendaftarResource extends JsonResource
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
            'seleksi_id' => $this->seleksi->id,
            'seleksi_tahun' => $this->seleksi->tahun,
            'seleksi_nama' => $this->seleksi->nama,

            'peserta_id' => $this->peserta->id,
            'peserta_noid' => $this->peserta->noid,

            'user_id' => $this->peserta->user->id,
            'user_name' => $this->peserta->user->name,
            'user_email' => $this->peserta->user->email,

            'sub_institusi_id' => $this->peserta->subInstitusi->id,
            'sub_institusi_jenis' => $this->peserta->subInstitusi->jenis,
            'sub_institusi_nama' => $this->peserta->subInstitusi->nama,

            'institusi_id' => $this->peserta->subInstitusi->institusi->id,
            'institusi_nama' => $this->peserta->subInstitusi->institusi->nama,
            'institusi_is_negeri' => $this->peserta->subInstitusi->institusi->is_negeri,

            'identitas_id' => $this->peserta->user->identitas->id,
            'identitas_alamat' => $this->peserta->user->identitas->alamat,
            'identitas_foto' => $this->peserta->user->identitas->foto,
            'identitas_hp' => $this->peserta->user->identitas->hp,
            'identitas_tgl_lahir' => $this->peserta->user->identitas->tgl_lahir,
            'identitas_jenis_kelamin' => $this->peserta->user->identitas->jenis_kelamin,

            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
