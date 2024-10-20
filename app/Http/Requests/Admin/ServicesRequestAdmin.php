<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ServicesRequestAdmin extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'slug' => 'required|string',
            'status' => 'nullable|in:on',
            'short_description' => 'required|string|max:300',
            'meta_title' => 'required|string|max:255',
            'meta_description' => 'required|string|max:500',
        ];
    }
    public function messages()
    {
        return [
            '*.required' => ':attribute là bắt buộc.',
            '*.string' => ':attribute phải là chuỗi ký tự.',
            '*.max' => ':attribute không được dài quá :max ký tự.',
            '*.exists' => ':attribute không hợp lệ.',
            '*.in' => ':attribute không đúng.',
            '*.image' => ':attribute phải là một hình ảnh.',
            '*.mimes' => ':attribute phải có định dạng: :values.',
        ];
    }
    public function attributes()
    {
        return [
            'name' => 'Tên dịch vụ',
            'slug' => 'Đường dẫn',
            'short_description' => 'Mô tả ngắn',
            'status' => 'Trạng thái',
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
        ];
    }
}
