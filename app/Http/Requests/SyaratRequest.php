<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SyaratRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'nama' => 'required|string',
            'is_wajib' => 'required|integer',
            'seleksi_id' => 'required|integer',
            'keterangan' => 'nullable|string',
        ];
        if ($this->isMethod('put')) {
            $rules = [
                'nama' => 'nullable|string',
                'is_wajib' => 'nullable|integer',
                'seleksi_id' => 'nullable|integer',
                'keterangan' => 'nullable|string',
            ];
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'nama' => 'syarat',
            'is_wajib' => 'status wajib',
            'seleksi_id' => 'seleksi',
            'keterangan' => 'keterangan',
        ];
    }
}
