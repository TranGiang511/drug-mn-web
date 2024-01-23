<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Post as RulesPost;

class ContactRequest extends FormRequest
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
            'name' => 'required|max:50',
            'email' => 'required|email|max:50',
            'phone' => 'required|digits:10',
            'title' => 'required|string|regex:/^(?=.*[A-Za-z0-9]).+$/|max:255',
            'message' => [
                'required', 
                'string', 
                new RulesPost
            ],
            'g-recaptcha-response' => ['required']
            // 'g-recaptcha-response' => ['required', new \App\Rules\ValidRecaptcha]

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Không để trống',
            'name.max' => 'Giới hạn 50 ký tự',
            'email.required' => 'Không được bỏ trống email',
            'email.email' => 'Bắt buộc phải là email',
            'email.max' => 'Giới hạn 50 ký tự',
            'phone.required' => 'Không để trống',
            'phone.digits' => 'Vui lòng nhập đúng định dạng số điện thoại phải có 10 số',
            'title.required' => 'Không được bỏ trống tiêu đề',
            'title.regex' => 'Phải chứa ít nhất 1 ký tự hoặc số',
            'title.max' => 'Không quá 255 ký tự',
            'message.required' => "Không được bỏ trống nội dung",
            'g-recaptcha-response.required' => "Không được bỏ trống"
        ];
    }
}
