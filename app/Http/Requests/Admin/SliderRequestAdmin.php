<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SliderRequestAdmin extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'nullable|string|max:50',
            'price' => 'nullable|integer',
            'category' => 'nullable|integer',
            'description' => 'nullable|string|max:100',
            'button_text' => 'nullable|string|max:50',
            'status' => 'required|in:on,off',
            'button_link_text' => 'nullable|url',
            'order' => 'required|integer|min:0',
        ];
    }
    public function messages()
    {
        return [
            '*.required' => ':attribute là bắt buộc.',
            '*.string' => ':attribute phải là chuỗi ký tự.',
            '*.integer' => ':attribute phải là số.',
            '*.max' => ':attribute không được dài quá :max ký tự.',
            '*.min' => ':attribute tối thiểu :min ký tự.',
            '*.exists' => ':attribute không hợp lệ.',
            '*.in' => ':attribute không đúng.',
            '*.url' => ':attribute phải là đường dẫn hợp lệ.',
        ];
    }
    public function attributes()
    {
        return [
            'title' => 'Tiêu đề',
            'price' => 'Giá',
            'category' => 'Danh mục',
            'description' => 'Mô tả ngắn',
            'status' => 'Trạng thái',
            'button_text' => 'Nội dung nút',
            'button_link_text' => 'Đường dẫn nút',
            'order' => 'Thứ tự',
        ];
    }

}
