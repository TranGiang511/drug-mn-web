@extends('layouts.admin.app')

@section('title')
Manage Contact
@endsection

@section('content')
<article class="content">
    <div class="card">
        <h3 class="text-IBM" > Thông tin liên hệ ID : {{ $contact->id }}</h3>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-block ">
                <table class="table">
                    <tr>
                        <td class="w-25">ID:</td>
                        <td> {{ $contact->id }}</td>
                    </tr>
                    <tr>
                        <td>Họ tên:</td>
                        <td>{{ $contact->name }}</td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td> {{ $contact->email }}</td>
                    </tr>
                    <tr>
                        <td>Số điện thoại:</td>
                        <td> {{ $contact->phone }}</td>
                    </tr>
                    <tr>
                        <td>Tiêu đề:</td>
                        <td> 
                            <div style="word-break: break-all;">
                                {{ $contact->title }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Nội dung:</td>
                        <td> 
                            <div style="word-break: break-all;">
                                {{ $contact->content }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Created at :</td>
                        <td> {{ $contact->created_at }}</td>
                    </tr>
                    <tr>
                        <td>Uadated at :</td>
                        <td> {{ $contact->updated_at }}</td>
                    </tr>
                    <tr>
                        <td>Deleted at :</td>
                        <td> {{ $contact->deleted_at }}</td>
                    </tr>
                </table>
                <div class="right">
                    <a href='{{ url("/contact") }}' class="btn btn-primary">Quay lại</a>
                </div>
            </div>
        </div>
    </div>   
</article>
@endsection