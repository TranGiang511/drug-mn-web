<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ResgiterController extends Controller
{
    public function index()
    {
        $infos = null;
        $infos = DB::table('info')
            ->whereNull('deleted_at')
            ->get();
        if (!Auth::check()) {
            return view('auth.register', [
                'infos' => $infos,
            ]);
        } else {
            return redirect('/home');
        }
    }

    public function register(RegisterRequest $request)
    {
        $password = $request->input('password');
        $confirm_password = $request->input('confirm_password');

        if ($password == $confirm_password) {
            $user = DB::table('users')->insertGetId([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'password' => bcrypt($password),
                'role' => 'user'
            ]);

            Auth::loginUsingId($user);
            return redirect('/home');
        } else
            return redirect('/register')->withErrors(['invalid' => 'Kiểm tra lại xác thực mật khẩu']);
    }
}
