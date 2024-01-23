@extends('layouts.home.app')

@section('title')
Login
@endsection

@section('own_style')
<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
<style>
    .form-control:focus {
        box-shadow: 0 0 0 0.1rem #62a5f3;
    }
</style>
@endsection

@section('content')
<!-- Form Login Start -->
<div class="container-xxl position-relative p-0">
    <div class="container-xxl py-5 hero-header">
        <div class="p-4"></div>
        <div class="container my-5 pt-4 pb-4">
            <div class="row d-flex justify-content-center">
                <!--Form login-->
                <form id="form-login" method="POST" action="{{ url('login')}}" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-6 mb-3 border" style="border-radius: 5px; padding: 0px 50px; background-color: white; border-color: white;">
                            <!-- Show Notification -->
                            @if(session('success'))
                                <div class="alert alert-warning" id="alert-message">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-warning" id="alert-message">
                                    {{ session('error') }}
                                </div>
                            @endif
                            @if($errors->has('invalid'))
                                <div class="alert alert-danger" id="alert-message">
                                    {{ $errors->first('invalid') }}
                                </div>
                            @endif

                            <h1 class="text-center mb-4 mt-5" style="color: #2172cd">Login</h1>
                            <!--Email-->
                            <div class="form-floating mb-3">
                                <input type="email" name="email" class="form-control" id="email" placeholder="Your email" value="{{ old('email') }}" autocomplete="off">
                                <label for="email">Email</label>
                                <small id="error_email" class="text-warning"></small>
                                @error('email')
                                    <small id="error_email" class="text-warning">{{ $message }}</small>
                                @enderror
                            </div>
                            <!--Password-->
                            <div class="form-floating mb-4">
                                <input type="password" name="password" class="form-control" id="password" placeholder="Your password" value="{{ old('password') }}" autocomplete="off">
                                <label for="password">Password</label>
                                <small id="error_password" class="text-warning"></small>
                                @error('password')
                                    <small id="error_password" class="text-warning">{{ $message }}</small>
                                @enderror
                                @error('invalid')
                                    <small class="text-warning">{{ $message }}</small>
                                @enderror
                            </div>
                            <!--Remember, forgot passord-->
                            <div class="mb-4 justify-content-between d-flex small ml-3">
                                <input type="checkbox" class="form-check-input" checked="">
                                <label class="text-black-50">Remember me</label>
                                <a class="text-black-50" href="{{ route('reset_password') }}">Reset password?</a>
                            </div>
                            <!--Button login-->
                            <button class="btn btn-secondary w-100 mb-4" type="submit">Login</button>
                            <!--With sign-->
                            <div class="mb-4 text-center">Or with Sign</div>
                            <div class="mb-4 justify-content-around d-flex">
                                <a style="color:rgba(2, 31, 75, 0.87)" href="#">FACEBOOK</a>
                                <a class="text-info" href="#">TWITTER</a>
                                <a class="text-warning" href="#">GOOGLE+</a>
                            </div>
                            <div class="mb-5 text-center small">Don't have an account <a class="text-primary" href="{{url('register')}}">Register</a></div>
                            <!--Result-->
                            <div id="result" class="mb-4"></div>
                        </div>
                        <div class="col-md-3"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Form Login End -->

<!-- Ẩn thông báo sau 5s -->
<script>
    if (document.getElementById('alert-message')) {
        setTimeout(function() {
            document.getElementById('alert-message').style.display = 'none';
        }, 5000);
    }
</script>
@endsection
