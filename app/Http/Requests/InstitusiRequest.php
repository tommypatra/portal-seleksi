<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InstitusiRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'nama' => 'required|string',
            'is_negeri' => 'required|integer',
        ];
        if ($this->isMethod('put')) {
            $rules = [
                'nama' => 'nullable|string',
                'is_negeri' => 'nullable|integer',
            ];
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'nama' => 'nama institusi',
            'is_negeri' => 'status negeri',
        ];
    }
}
