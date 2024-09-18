<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TopikInterviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'bobot' => 'required|integer',
            'bank_soal_id' => 'required|integer',
            'seleksi_id' => 'required|integer',
            'keterangan' => 'nullable|string',
        ];
        return $rules;
    }

    public function attributes()
    {
        return [
            'bobot' => 'bobot',
            'bank_soal_id' => 'bank soal id',
            'seleksi_id' => 'seleksi',
            'keterangan' => 'keterangan',
        ];
    }
}
