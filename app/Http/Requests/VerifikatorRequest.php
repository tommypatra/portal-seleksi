<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifikatorRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'id' => 'required|integer',
            'verifikasi_valid' => 'required|integer',
        ];

        $rules['verifikasi_keterangan'] = (request()->input('verifikasi_valid')) ? 'nullable|string' : 'required|string';

        return $rules;
    }

    public function attributes()
    {
        return [
            'id' => 'file upload berkas',
            'verifikasi_valid' => 'status verifikasi',
            'verifikasi_keterangan' => 'keterangan verifikasi',
        ];
    }
}
