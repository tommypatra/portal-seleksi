<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SeleksiAsalRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'seleksi_id' => 'required|integer',
            'sub_institusi_id' => 'required|integer',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'seleksi_id' => 'seleksi',
            'sub_institusi_id' => 'asal',
        ];
    }
}
