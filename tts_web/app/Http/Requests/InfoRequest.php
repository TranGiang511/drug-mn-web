<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Post as RulesPost;

class InfoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'title' => 'required|max:50',
            'title_e' => 'required|max:50',
            'content' => 'required',
            'content_e' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Không để trống',
            'title.max' => 'Giới hạn 50 ký tự',
            'content.required' => 'Không được bỏ trống',
            'title_e.required' => 'Không để trống',
            'title_e.max' => 'Giới hạn 50 ký tự',
            'content_e.required' => 'Không được bỏ trống',
        ];
    }
}
