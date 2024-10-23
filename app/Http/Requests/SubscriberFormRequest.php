<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriberFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'fullname_subscriber' => [
                'required',
                'string',
                'max:30',
                'regex:/^[A-Za-zÀ-ỹ\s]+$/u'
            ],
            'email_subscriber' => [
                'required',
                'email',
                'max:255',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'fullname_subscriber.required' => 'Họ và tên không được để trống.',
            'fullname_subscriber.string'   => 'Họ và tên phải là chuỗi ký tự.',
            'fullname_subscriber.max'      => 'Họ và tên không được vượt quá 30 ký tự.',
            'fullname_subscriber.regex'    => 'Phải nhập đúng họ và tên, chỉ bao gồm chữ cái và khoảng trắng.',

            'email_subscriber.required' => 'Email không được để trống.',
            'email_subscriber.email'    => 'Email phải đúng định dạng.',
            'email_subscriber.max'      => 'Email không được vượt quá 255 ký tự.',
        ];
    }

}
