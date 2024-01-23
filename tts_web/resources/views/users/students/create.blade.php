
@extends('layouts.admin.app')

@section('title')
User
@endsection

@section('content')
<article class="content forms-page">
    <div class="card">
        <h1 class="text-IBM">Thêm học viên</h1>
    </div>
    <div class="row sameheight-container">
        <div class="col-md-12">
            <div class="card card-block sameheight-item">
                <form action="{{ url("/users/student") }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">Họ Tên</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" placeholder="Name" aria-describedby="helpId">
                    </div>
                    @error('name')
                        <div class="form-text text-danger mb-3">{{ $message }}</div>
                    @enderror

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control" placeholder="Email" aria-describedby="helpId">
                    </div>
                    @error('email')
                        <div class="form-text text-danger mb-3">{{ $message }}</div>
                    @enderror
                    
                    <div class="form-group">
                        <label for="phone">Số điện thoại</label>
                        <input type="phone" name="phone" id="phone" value="{{ old('phone') }}" class="form-control" placeholder="Phone number" aria-describedby="helpId">
                    </div>
                    @error('phone')
                        <div class="form-text text-danger mb-3">{{ $message }}</div>
                    @enderror

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password" aria-describedby="helpId">
                    </div>
                    @error('password')
                        <div class="form-text text-danger mb-3">{{ $message }}</div>
                    @enderror

                    <div class="right">
                        <button type="submit" class="btn btn-primary" name="save">Lưu thông tin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</article>
@endsection