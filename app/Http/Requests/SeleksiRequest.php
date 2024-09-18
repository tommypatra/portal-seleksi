<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SeleksiRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'nama' => 'required|string',
            'tahun' => 'required|date_format:Y',
            'daftar_mulai' => 'required|date_format:Y-m-d',
            'daftar_selesai' => 'required|date_format:Y-m-d',
            'verifikasi_mulai' => 'required|date_format:Y-m-d',
            'verifikasi_selesai' => 'required|date_format:Y-m-d',
            'jenis_id' => 'required|integer',
            'is_publish' => 'required|integer',
            'keterangan' => 'nullable',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'nama' => 'nama penerimaan',
            'tahun' => 'tahun',
            'daftar_mulai' => 'tanggal daftar mulai',
            'daftar_selesai' => 'tanggal daftar selesai',
            'verifikasi_mulai' => 'tanggal verifikasi mulai',
            'verifikasi_selesai' => 'tanggal verifikasi selesai',
            'jenis_id' => 'jenis penerimaan',
            'keterangan' => 'keterangan',
            'is_publish' => 'status publikasi',
        ];
    }
}
