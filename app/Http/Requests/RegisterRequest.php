<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'firstname' => 'required|min:3|max:100',
            'lastname' => 'required|min:3|max:100',
            'address' => 'min:3|max:255',
            'email' => 'required|unique:users|min:3|max:255',
            'no_phone' => 'min:9|max:20',
            'password' => 'required|confirmed|min:3|max:225',
        ];
    }
}
