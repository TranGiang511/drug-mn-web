<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;


class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Kiểm tra xem tài khoản có cần đăng xuất không
        if (session('logout_user_id')) {
            Auth::logout(); // Đăng xuất tài khoản
            $request->session()->forget('logout_user_id'); // Xóa thông tin đăng xuất
        }

        $infos = null;
        $infos = DB::table('info')
            ->whereNull('deleted_at')
            ->get();

        // check locale
        $allowedLocales = ['vi', 'en'];
        $locale = in_array(session('locale'), $allowedLocales) ? session('locale') : config('app.locale');
        if (!in_array($locale, $allowedLocales)) {
            $news_banner = null;
            $count_news_banner = 0;
            $news = null;
            $countPost = 0;
        } else {
            $tableName = ($locale === 'en') ? 'news_tran' : 'news';

            $news_banner = DB::table($tableName)
                ->select($tableName . '.*')
                ->whereNull($tableName . '.deleted_at')
                ->where('banner', 1)
                ->where('public_check', '1')
                ->latest('created_at')
                ->limit(3)
                ->get();
            $count_news_banner = DB::table($tableName)
                ->whereNull($tableName . '.deleted_at')
                ->where('banner', 1)
                ->where('public_check', '1')
                ->count();

            $news = DB::table($tableName)
                ->join('users', 'users.id', '=', $tableName . '.id_user')
                ->select($tableName . '.*', 'users.name', 'users.role')
                ->whereNull($tableName . '.deleted_at')
                ->where('public_check', '1')
                ->where('public_date', '<=', Carbon::now())
                ->latest('created_at')
                ->limit(3)
                ->get();
            $countPost = DB::table($tableName)
                ->whereNull($tableName . '.deleted_at')
                ->where('public_check', '1')
                ->where('public_date', '<=', Carbon::now())
                ->count();
        }

        return view('index', [
            'news_banner' => $news_banner,
            'count_news_banner' => $count_news_banner,
            'news' => $news,
            'countPost' => $countPost,
            'locale' => $locale,
            'infos' => $infos,
        ]);
    }

    public function switch_lang($lang, Request $request)
    {
        $validLocales = ['en', 'vi', 'jp'];
        if (in_array($lang, $validLocales)) {
            // Lưu ngôn ngữ vào session
            Session::put('locale', $lang);
        } else {
            // Nếu $lang không hợp lệ, đặt ngôn ngữ mặc định là 'vi' (tiếng Việt)
            Session::put('locale', config('app.locale'));
        }
        return redirect()->back();
    }

}
