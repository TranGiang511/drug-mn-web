@extends('layouts.home.app')

@section('title')
Resgiter
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
<!-- Form Register Start -->
<div class="container-xxl position-relative p-0">
    <div class="container-xxl py-5 hero-header">
        <div class="p-4"></div>
        <div class="container my-5 pt-4 pb-4">
            <div class="row d-flex justify-content-center">
                <!--Form Register-->
                <form id="form-register" class="needs-validation"
                    action="{{ url('register') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-6 mb-3 border border-2 bg-light"
                            style="border-radius: 5px; padding: 0px 50px;">
                            <h1 class="text-center mb-4 mt-5" style="color: #2172cd">Register</h1>
                            <!--Name-->
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control"
                                    id="name" placeholder="Your Name" name="name" value="{{ old('name') }}">
                                <label for="name">Your name</label>
                                <small id="error_name" class="text-warning"></small>
                                @error('name')
                                    <small id="error_name" class="text-warning">{{ $message }}</small>
                                @enderror
                            </div>
                            <!--Email-->
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control"
                                    id="email" placeholder="Your Email" name="email" value="{{ old('email') }}">
                                <label for="email">Your Email</label>
                                <small id="error_email" class="text-warning"></small>
                                @error('email')
                                    <small id="error_email" class="text-warning">{{ $message }}</small>
                                @enderror
                            </div>
                            <!--Phone-->
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control"
                                    id="phone" placeholder="Your phone" name="phone" value="{{ old('phone') }}">
                                <label for="phone">Your phone</label>
                                <small id="error_phone" class="text-warning"></small>
                                @error('phone')
                                    <small id="error_phone" class="text-warning">{{ $message }}</small>
                                @enderror
                            </div>
                            <!--Password-->
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control"
                                    id="password" placeholder="Your password" name="password" value="{{ old('password') }}">
                                <label for="password">Password</label>
                                <small id="error_password" class="text-warning"></small>
                                @error('password')
                                    <small id="error_password" class="text-warning">{{ $message }}</small>
                                @enderror
                            </div>
                            <!--Confirm Password-->
                            <div class="form-floating mb-4">
                                <input type="password" class="form-control"
                                    id="confirm_password"
                                    placeholder="Your password" name="confirm_password" value="{{ old('confirm_password') }}">
                                <label for="confirm_password">Confirm Password</label>
                                <small id="error_confirm_password" class="text-warning"></small>
                                @error('confirm_password')
                                    <small id="error_confirm_password" class="text-warning">{{ $message }}</small>
                                @enderror 
                                @error('invalid')
                                    <small class="text-warning">{{ $message }}</small>
                                @enderror
                            </div>
                            <!--agree terms & conditions-->
                            <div class="justify-content-between d-flex small ml-4" style="margin: 0;">
                                <input type="checkbox" class="form-check-input" name="check_box" id="check_box">
                                <label class="text-black-50">I agree terms & conditions</label>
                            </div>
                            <small id="error_check_box" class="text-warning"></small>
                            <!--Button login-->
                            <button class="btn btn-secondary w-100 mb-4 mt-4" type="submit">Create</button>
                            <!--With sign-->
                            <div class="mb-4 text-center">Or with Sign</div>
                            <div class="mb-4 justify-content-around d-flex">
                                <a style="color:rgba(2, 31, 75, 0.87)" href="#">FACEBOOK</a>
                                <a class="text-info" href="#">TWITTER</a>
                                <a class="text-warning" href="#">GOOGLE+</a>
                            </div>
                            <div class="mb-4 text-center small">
                                have an account? <a href="{{ url('login') }}" class="text-primary">Login</a>
                            </div>
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
<!-- Form Register End -->

{{-- <script src="js/form-resgiter.js"></script> --}}
@endsection
