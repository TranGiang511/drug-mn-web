<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class LogoutWhenResetPassword
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user() != null) {
            $user = DB::table('users')
            ->where('id', Auth::id())
            ->first();

            $loginUser = DB::table('users')
                ->join('login_session', 'users.id', '=', 'login_session.id_user')
                ->where('users.id', $user->id)
                ->where('login_session.token', session('access_token'))
                ->first();

            if ($loginUser) {
                return $next($request);
            } else {
                Auth::logout();
                return redirect('/login')->with('error', 'Mật khẩu đã thay đổi. Vui lòng đăng nhập lại!');
            }
        }
        return $next($request);
    }
}
