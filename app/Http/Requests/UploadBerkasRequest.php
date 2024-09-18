<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadBerkasRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'pendaftar_id' => 'required|integer',
            'syarat_id' => 'required|integer',
            'verifikasi_valid' => 'nullable|integer',
            'verifikasi_keterangan' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,gif|max:10240',
        ];
        if ($this->isMethod('post')) {
            $rules['file'] = 'required|file|mimes:pdf,jpg,jpeg,png,gif|max:10240';
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'pendaftar_id' => 'pendaftar',
            'syarat_id' => 'syarat',
            'file' => 'dokumen upload',
            'verifikasi_valid' => 'status verifikasi',
            'verifikasi_keterangan' => 'keterangan verifikasi',
        ];
    }
}
