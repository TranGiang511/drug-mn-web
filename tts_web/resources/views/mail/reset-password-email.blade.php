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
    <p>Bạn đã yêu cầu đặt lại mật khẩu cho tài khoản của mình trên trang web ViniCorp của chúng tôi. Để đặt lại mật khẩu của bạn, vui lòng nhấp vào nút bên dưới:</p>
    <a href="{{ $resetLink }}" style="background-color: #007BFF; color: #ffffff; text-align: center; display: inline-block; border-radius: 4px; padding: 10px 20px; text-decoration: none;">Reset Password</a>
    <p>Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.</p>
    <p>Thanks,</p>
    <p>The {{ config('app.name') }} Team</p>
    <p>Website: <a href="{{ route('home') }}">ViniCorp Website</a></p>
    {{-- @endcomponent --}}
</body>
</html>
