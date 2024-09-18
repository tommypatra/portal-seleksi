<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubInstitusiRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'nama' => 'required|string',
            'jenis' => 'required|string',
            'keterangan' => 'nullable|string',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'nama' => 'nama institusi',
            'jenis' => 'jenis',
            'keterangan' => 'keterangan',
        ];
    }
}
