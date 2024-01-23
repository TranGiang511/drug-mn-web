<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;

class AfterOrEqualToday implements Rule
{
    public function passes($attribute, $value)
    {
        // Chuyển đổi ngày thành timestamp chỉ có ngày, tháng, năm (không lấy giờ phút giây)
        $selectedDate = date('Y-m-d', strtotime($value)); // Convert selected date to YYYY-mm-dd format without time
        $currentDate = date('Y-m-d', time()); // Get current date in YYYY-mm-dd format without time

        return $selectedDate > $currentDate;
    }

    public function message()
    {
        return 'Ngày công bố phải lớn hơn hoặc bằng ngày hiện tại.';
    }
}
