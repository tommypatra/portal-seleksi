<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WawancaraRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'nilai' => 'nullable|integer',
            'pendaftar_id' => 'required|exists:pendaftars,id',
            'role_seleksi_id' => 'required|exists:role_seleksis,id',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'nilai' => 'nilai hasil',
            'pendaftar_id' => 'pendaftar',
            'role_seleksi_id' => 'interviewer',
        ];
    }
}
