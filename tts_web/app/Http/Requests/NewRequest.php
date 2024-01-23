<?php

namespace App\Http\Requests;

use App\Rules\AfterOrEqualToday;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Post as RulesPost;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class NewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
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
            'title' => 'required|string|regex:/^(?=.*[A-Za-z0-9]).+$/|max:255',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif',
            'content' => [
                'required', 
                'string', 
                new RulesPost
            ],
            'public_date' => [
                'required',
                new AfterOrEqualToday,
            ],
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Không được bỏ trống tiêu đề',
            'title.regex' => 'Phải chứa ít nhất 1 ký tự hoặc số',
            'title.max' => 'Không quá 255 ký tự',
            'thumbnail.required' => 'Không được bỏ trống thumbnail',
            'thumbnail.image' => 'Phải là hình ảnh',
            'thumbnail.mimes' => 'Phải đúng định dạng ảnh jpeg,png,jpg,gif',
            'content.required' => "Không được bỏ trống nội dung",
            'public_date.required' => 'Không được bỏ trống ngày công bố',
        ];
    }
}
