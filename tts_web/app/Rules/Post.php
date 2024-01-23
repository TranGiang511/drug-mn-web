<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Post implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $check = str_replace('&nbsp;',' ', $value);
        $check = trim($check);
        return strlen($check) == 0 ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Vui lòng không để trống';
    }
}
