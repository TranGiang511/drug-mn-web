<?php

namespace App\Http\Requests;

use App\Rules\ContainUppercaseAndAtLeastOneNumber;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'name' => 'required',
            'phone' => 'required|digits:10',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', new ContainUppercaseAndAtLeastOneNumber],
            'confirm_password' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Không để trống',
            'phone.required' => 'Không để trống',
            'phone.digits' => 'Vui lòng nhập đúng định dạng số điện thoại phải có 10 số',
            'email.required' => 'Không được bỏ trống email',
            'email.email' => 'Bắt buộc phải là email',
            'email.unique' => 'Email đã tồn tại',
            'password.required' => 'Không để trống',
            'confirm_password.required' => 'Không để trống',
        ];
    }
}
