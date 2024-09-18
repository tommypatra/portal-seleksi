<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PemeriksaSyaratRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'role_seleksi_id' => 'required|exists:role_seleksis,id',
            'pendaftar_id' => 'required|exists:pendaftars,id',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'role_seleksi_id' => 'verifikator',
            'pendaftar_id' => 'pendaftar',
        ];
    }
}
