<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingFormRequest extends FormRequest
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
            'date' => 'required|date_format:"d/m/Y H:i"',
            'content' => 'nullable|string|max:300',
        ];
    }
    public function messages()
    {
        return [
            'name.regex' => 'Họ tên phải chính xác',
            '*.required' => ':attribute là bắt buộc.',
            '*.string' => ':attribute phải là chuỗi ký tự.',
            '*.max' => ':attribute không được dài quá :max ký tự.',
            '*.exists' => ':attribute không hợp lệ.',
            '*.in' => ':attribute không đúng.',
            '*.image' => ':attribute phải là một hình ảnh.',
            '*.mimes' => ':attribute phải có định dạng: :values.',
            '*.date_format' => ':attribute phải đúng định dạng',
        ];
    }
    public function attributes()
    {
        return [
            'name' => 'Họ tên',
            'phone' => 'Số điện thoại',
            'email' => 'Email',
            'date' => 'Ngày đặt lịch',
            'content' => 'Nội dung đặt lịch',
        ];
    }
}
