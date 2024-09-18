<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PendaftarRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'tahun' => 'required|integer',
            'seleksi_id' => 'required|integer',
            'peserta_id' => 'required|integer',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'tahun' => 'tahun pelaksanaan',
            'seleksi_id' => 'seleksi',
            'peserta_id' => 'peserta',
        ];
    }
}
