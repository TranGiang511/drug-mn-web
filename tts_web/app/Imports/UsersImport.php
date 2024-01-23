<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;

class UsersImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    use Importable;

    public function model(array $row)
    {
        User::updateOrInsert(
            ['email' => $row['email']], // Unique key to check
            [
                'name' => $row['name'],
                'phone' => $row['phone'],
                'password' => bcrypt($row['password']),
                'role' => $row['role'],
                'deleted_at' => null,
            ]
        );
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
            ],
            'phone' => ['required', 'max:10', 'min:9', 'regex:/^[0-9]*$/'],
            'role'=>['nullable','string']
        ];
    }

    public function customValidationMessages()
    {
        return [
            'name.required' => 'Tên là trường bắt buộc.',
            'name.string' => 'Tên phải là chuỗi.',
            'name.max' => 'Tên không được vượt quá 255 ký tự.',
            
            'email.required' => 'Email là trường bắt buộc.',
            'email.string' => 'Email phải là chuỗi.',
            'email.email' => 'Email phải là địa chỉ email hợp lệ.',
            'email.max' => 'Email không được vượt quá 255 ký tự.',
            
            'password.required' => 'Mật khẩu là trường bắt buộc.',
            'password.string' => 'Mật khẩu phải là chuỗi.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.regex' => 'Mật khẩu phải chứa ít nhất 1 chữ thường, 1 chữ in hoa, 1 số và 1 ký tự đặc biệt.',
            
            'phone.required' => 'Số điện thoại là trường bắt buộc.',
            'phone.max' => 'Số điện thoại không được vượt quá 10 ký tự.',
            'phone.min' => 'Số điện thoại phải có ít nhất 9 ký',
            'phone.regex' => 'Số điện thoại phải chứa chỉ các chữ số.',
            
            'role.string' => 'Vai trò phải là chuỗi.',
        ];
    }
}
