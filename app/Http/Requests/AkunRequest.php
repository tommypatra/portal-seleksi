<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AkunRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'name' => 'required|string',
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ];

        if ($this->isMethod('post')) {
            $rules['email'] .= '|unique:users,email';
        }

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['password'] = 'nullable|string|min:8';
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'name' => 'nama akun',
            'email' => 'email akun',
            'password' => 'kata sandi',
        ];
    }
}
