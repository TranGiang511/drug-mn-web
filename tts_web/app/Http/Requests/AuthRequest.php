<?php

namespace App\Http\Requests;

use App\Rules\ContainUppercaseAndAtLeastOneNumber;
use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
            'email' => 'required|email',
            'password' => ['required', new ContainUppercaseAndAtLeastOneNumber],
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Không được bỏ trống email',
            'email.email' => 'Bắt buộc phải là email',
            'password.required' => "Không được bỏ trống mật khẩu"
        ];
    }
}
