<?php 
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        
        $defaultLocale = Config::get('app.locale');

        $validLocales = Config::get('app.valid_locales', ['vi']);

        $locale = Session::get('locale', $validLocales);

        // Kiểm tra xem $locale có trong danh sách ngôn ngữ hợp lệ không
        if (!in_array($locale, $validLocales)) {
            $locale = $defaultLocale; // Sử dụng giá trị mặc định nếu không hợp lệ
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
