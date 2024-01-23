@extends('layouts.admin.app')

@section('title')
Show
@endsection

@section('content')
<article class="content">
    <div class="card">
        <h1 class="text-IBM"> Thông tin cá nhân</h1>
    </div>
    <div class="card card-block sameheight-item">
        <table class="table">
            <tr>
                <td class="w-25">ID:</td>
                <td> {{ $user->id }}</td>
            </tr>
            <tr>
                <td>Họ tên:</td>
                <td>{{ $user->name }}</td>
            </tr>
            <tr>
                <td>Email:</td>
                <td> {{ $user->email }}</td>
            </tr>
            <tr>
                <td>Số điện thoại:</td>
                <td> {{ $user->phone }}</td>
            </tr>
        </table>
        <div class="right">
            <a href="{{url("/users/self_edit/" . Auth::user()->id)}}" class="btn btn-primary"> Thay đổi thông tin</a>
        </div>
    </div>
</article>
@endsection