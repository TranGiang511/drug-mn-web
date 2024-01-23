<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Mail\EmailRandPassword;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Mail\EmailResetPassword;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function index()
    {
        $infos = null;
        $infos = DB::table('info')
            ->whereNull('deleted_at')
            ->get();
        if (Auth::check()) {
            return redirect('/home');
        } else {
            return view('auth.login', [
                'infos' => $infos,
            ]);
        }
    }

    public function login(AuthRequest $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $request->session()->regenerate();
            $user = Auth::user();
            $token = Str::random(30);

            // Lưu token vào session để xử lý bên middleware 
            session()->put('access_token', $token);

            DB::table('login_session')
                ->insert([
                    'id_user' => $user->id,
                    'token' => $token,
                ]);
            return redirect('/home');
        } else
            return redirect('/login')->withErrors(['invalid' => 'Kiểm tra lại Email hoặc Password']);
    }

    public function logout(Request $request)
    {
        $token = $request->session()->get('access_token');

        DB::table('login_session')
            ->where('token', $token)
            ->delete();

        Auth::logout();
        Session::flush();
        return redirect('/home');
    }


    /* 
        Reset password token 
    */
    public function reset_password_show()
    {
        $infos = null;
        $infos = DB::table('info')
            ->whereNull('deleted_at')
            ->get();
        // Tạo mật khẩu ngẫu nhiên
        $randomPassword = $this->generateRandomPassword();
        return view('auth.reset_password', [
            'infos' => $infos,
            'randomPassword' => $randomPassword
        ]);
    }

    public function reset_password_submit(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);
        $email = $request->input('email');
        $user = DB::table('users')
            ->where('email', $email)
            ->whereNull('deleted_at')
            ->first();
        if ($user) {
            $token = Str::random(64);

            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $request->email],
                [
                    'token' => $token,
                    'created_at' => Carbon::now(),
                ]
            );

            // Create the reset link
            $resetLink = url("/password/reset/{$email}/{$token}");

            // Send the reset password email with the reset link
            $email_template = new EmailResetPassword($request->email, $token, $resetLink);
            Mail::to($request->email)->send($email_template);

            return redirect()->back()->with('success', 'Password reset email sent successfully.');
        } else {
            return redirect()->back()->with('error', 'Email address not found in the system.');
        }
    }

    public function change_the_password_show($email, $token)
    {
        $infos = null;
        $infos = DB::table('info')
            ->whereNull('deleted_at')
            ->get();
        $resetToken = DB::table('password_reset_tokens')
            ->where('token', $token)
            ->first();
        if ($resetToken) {
            $createdTime = Carbon::parse($resetToken->created_at);
            $expiredTime = $createdTime->addMinutes(10); // Thời gian hết hạn sau 10 phút

            if (Carbon::now()->gt($expiredTime)) {
                // Thời gian hết hạn, hiển thị thông báo
                return redirect('/reset_password')->withErrors(['invalid' => 'Quá thời gian reset password (10 phút)']);
            }
            return view('auth.update_password', 
                compact(
                    'email', 
                    'token',
                    'infos'
                )
            );
        } else {
            return redirect('/reset_password')
                ->withErrors(['invalid' => 'Token không hợp lệ']);
        }
    }

    public function change_the_password_submit(ResetPasswordRequest $request)
    {
        $email = $request->email;
        $token = $request->token;

        $resetToken = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', $token)
            ->first();

        if ($resetToken) {
            $new_password = $request->new_password;
            $confirm_password = $request->confirm_password;

            if ($new_password === $confirm_password) {
                $id = DB::table('users')
                    ->where('email', $email)
                    ->value('id');

                // Update the user's password
                DB::table('users')
                    ->where('email', $email)
                    ->whereNull('deleted_at')
                    ->update([
                        'password' => bcrypt($new_password),
                        'reset_password_status' => 1
                    ]);

                // Remove the password reset token
                DB::table('password_reset_tokens')
                    ->where('email', $email)
                    ->delete();

                // Remove the login session
                DB::table('login_session')
                    ->where('id_user', $id)
                    ->delete();

                return redirect('/login')->with('success', 'Tài khoản được cập nhật thành công.');
            } else {
                return redirect()->back()
                    ->withErrors(['invalid' => 'Kiểm tra lại xác thực mật khẩu']);
            }
        } else {
            return redirect()->back()
                ->withErrors(['invalid' => 'Email hoặc token không hợp lệ']);
        }
    }
    /* 
        End reset password token 
    */


    /* 
        Reset password random 
    */
    private function generateRandomPassword()
    {
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $specialChars = '!@#$%^&*()_-+=<>?/{}|';
        $password =
            substr(str_shuffle($lowercase), 0, 1) .
            substr(str_shuffle($uppercase), 0, 1) .
            substr(str_shuffle($numbers), 0, 1) .
            substr(str_shuffle($specialChars), 0, 1);
        // Tạo một pool chứa tất cả các ký tự và trộn lên đến đủ 10 ký tự
        $pool = str_shuffle($lowercase . $uppercase . $numbers . $specialChars);

        // Đảm bảo mật khẩu đạt đủ 10 ký tự
        while (strlen($password) < 10) {
            $password .= $pool[rand(0, strlen($pool) - 1)];
        }

        $shuffledPassword = str_shuffle($password);
        return $shuffledPassword;
    }

    public function reset_password_random_submit(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);
        $email = $request->email;
        $password_reset = $this->generateRandomPassword();

        if ($password_reset !== false) {
            $id = DB::table('users')
                ->where('email', $email)
                ->value('id');

            DB::table('users')
                ->where('email', $email)
                ->whereNull('deleted_at')
                ->update([
                    'password' => bcrypt($password_reset),
                    'reset_password_status' => 1
                ]);

            // Remove the login session
            DB::table('login_session')
                ->where('id_user', $id)
                ->delete();

            // Gửi email đặt lại mật khẩu kèm theo tên mail, mật khẩu reset
            $email_template = new EmailRandPassword($email, $password_reset);
            Mail::to($email)->send($email_template);

            return redirect('/login')->with('success', 'Tài khoản đã được cập nhật thành công.');
        } else {
            return redirect()->back()->withErrors(['invalid' => 'Có lỗi xảy ra trong quá trình đặt lại mật khẩu. Vui lòng thử lại.']);
        }
    }
    /* 
        End reset password random 
    */
}
