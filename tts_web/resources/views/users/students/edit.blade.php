@extends('layouts.admin.app')

@section('title')
User
@endsection

@section('content')
<article class="content forms-page">
    <div class="card">
        <h1 class="text-IBM" >Cập nhật thông tin học viên ID : {{ $student->id }}</h1>
    </div>
    <div class="row sameheight-container">
        <div class="col-md-12">
            <div class="card card-block sameheight-item">
                <form action="{{url("/users/student/$student->id")}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" value="{{ $student->id }}">
                    <div class="form-group">
                        <label for="name">Họ Tên</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Name" aria-describedby="helpId" value="{{ old('name',$student->name) }}">
                    </div>
                    @error('name')
                    <div class="form-text text-danger mb-3">{{ $message }}</div>
                    @enderror

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Email" aria-describedby="helpId" value="{{ old('email',$student->email) }}">
                    </div>
                    @error('email')
                    <div class="form-text text-danger mb-3">{{ $message }}</div>
                    @enderror

                    <div class="form-group">
                        <label for="phone">Số điện thoại</label>
                        <input type="phone" name="phone" id="phone" class="form-control" placeholder="Phone number" aria-describedby="helpId" value="{{ old('phone',$student->phone) }}">
                    </div>
                    @error('phone')
                        <div class="form-text text-danger mb-3">{{ $message }}</div>
                    @enderror

                    <div class="form-group">
                        <label for="new_password">Mật khẩu mới</label>
                        <input type="password" name="new_password" id="new_password" class="form-control" placeholder="new_password" aria-describedby="helpId" value="{{ old('new_password') }}">
                    </div>
                    @error('new_password')
                        <div class="form-text text-danger mb-3">{{ $message }}</div>
                    @enderror

                    <div class="form-group">
                        <label for="confirm_password">Xác nhận lại mật khẩu</label>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="confirm password" aria-describedby="helpId" value="{{ old('confirm_password') }}">
                    </div>
                    @error('confirm_password')
                        <div class="form-text text-danger mb-3">{{ $message }}</div>
                    @enderror
                    @error('invalid')
                        <div class="form-text text-danger mb-3">{{ $message }}</div>
                    @enderror

                    <div class="right">
                        <button type="submit" class="btn btn-primary">Lưu thông tin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</article>
@endsection