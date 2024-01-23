@extends('layouts.admin.app')

@section('title')
User
@endsection

@section('content')
<article class="content">
    <div class="card">
        <h3 class="text-IBM" > Thông tin học viên ID : {{ $student->id }}</h3>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-block ">
                <table class="table">
                    <tr>
                        <td class="w-25">ID:</td>
                        <td> {{ $student->id }}</td>
                    </tr>
                    <tr>
                        <td>Họ tên:</td>
                        <td>{{ $student->name }}</td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td> {{ $student->email }}</td>
                    </tr>
                    <tr>
                        <td>Số điện thoại:</td>
                        <td> {{ $student->phone }}</td>
                    </tr>
                    <tr>
                        <td>Created at :</td>
                        <td> {{ $student->created_at }}</td>
                    </tr>
                    <tr>
                        <td>Uadated at :</td>
                        <td> {{ $student->updated_at }}</td>
                    </tr>
                    <tr>
                        <td>Deleted at :</td>
                        <td> {{ $student->deleted_at }}</td>
                    </tr>
                </table>
                <div class="right">
                    <a href='{{ url("/users/student/{$student->id}/edit") }}' class="btn btn-primary">Thay đổi thông tin</a>
                </div>
            </div>
        </div>
    </div>   
</article>
@endsection