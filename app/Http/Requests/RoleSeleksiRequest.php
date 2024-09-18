<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleSeleksiRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'role_user_id' => 'required|integer',
            'seleksi_id' => 'required|integer',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'role_user_id' => 'role user',
            'seleksi_id' => 'seleksi',
        ];
    }
}
