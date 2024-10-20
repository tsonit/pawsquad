<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class SignupFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:30',
                'regex:/^[A-Za-zÀ-ỹ\s]+$/u'
            ],
            'email' => 'required|email|max:255',
            'phone' => 'required|numeric|digits_between:8,12|regex:/^[0-9]{8,12}$/',
            'password' => 'required|string|min:6',
        ];
    }
    public function messages(): array
    {
        return [
            'name.regex' => 'Phải nhập đúng họ và tên',
        ];
    }
}
