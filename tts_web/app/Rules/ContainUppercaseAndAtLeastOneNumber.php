<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ContainUppercaseAndAtLeastOneNumber implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#\$%\^&\*\(\)_\-\+=\[\]\{\};:\'",<>\.\\|]).+$/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Mật khẩu phải có ít nhất 1 chữ thường, 1 chữ in hoa, 1 số và 1 ký tự đặc biệt';
    }
}
