<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class EditDeleteNews
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle($request, Closure $next)
    {
        $id = $request->route('new');
        $news = DB::table('news')->where('id', $id)->first();
        if (!$news) {
            abort(404);
        }
        $id_news = $news->id_user;
        $role_user = DB::table('users')
        ->where('id', $id_news)
        ->value('role');


        if (Auth::user()->role === 'admin') {
            // Nếu người dùng là admin, cho phép chỉnh sửa bài viết của user hoặc chính họ
            if ($news->id_user !== Auth::id() && $role_user === 'admin') {
                return redirect()->route('news.index')->with('error', 'Bạn không thể chỉnh sửa hay xóa bài viết của admin khác');
            }
        } elseif ($news->id_user !== Auth::id()) {
            // Ngăn chặn người dùng thông thường chỉnh sửa bài viết của người khác
            return redirect()->route('news.index')->with('error', 'Bạn chỉ có thể chỉnh sửa bài viết của chính mình');
        }
        return $next($request);
    }
}