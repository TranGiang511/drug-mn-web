<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    {{-- @component('mail::message') --}}
    <p>Chào {{ $name }},</p>
    <p>Bạn đã yêu cầu đặt lại mật khẩu cho tài khoản của mình trên trang web ViniCorp của chúng tôi. Đây là mật khẩu mới:</p>
    <p>{{ $password_reset }}</p>
    <p>Khi đăng nhập vào hãy thay đổi lại mật khẩu!</p>
    <p>Thanks,</p>
    <p>The {{ config('app.name') }} Team</p>
    <p>Website: <a href="{{ route('home') }}">ViniCorp Website</a></p>
    {{-- @endcomponent --}}
</body>
</html>
