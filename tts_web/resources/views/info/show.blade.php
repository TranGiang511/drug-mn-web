@extends('layouts.admin.app')

@section('title')
Manage Info
@endsection

@section('content')
<style>
    .show_info .content_info {
        max-width: 100%;
        margin: 0;
        padding: 0;
    }

    .show_info .content_info img {
        max-width: 100%;
        height: auto;
    }
</style>
<article class="content">
    <div class="card">
        <h3 class="text-IBM" > Chi tiết thông tin : {{ $info->type }}</h3>
    </div>
    <div class="row sameheight-container">
        <div class="col-md-12">
            <div class="card card-block">
                <table class="table show_info">
                    <tr>
                        <td class="w-25">ID:</td>
                        <td> {{ $info->id }}</td>
                    </tr>
                    <tr>
                        <td>Họ tên:</td>
                        <td>{{ $info->type }}</td>
                    </tr>
                    <tr>
                        <td>Title:</td>
                        <td> {{ $info->title }}</td>
                    </tr>
                    <tr>
                        <td>Content:</td>
                        <td style="max-width: 100%">
                            <div class="content_info">
                                {!! $info->content !!}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Title English:</td>
                        <td> {{ $info->title_e }}</td>
                    </tr>
                    <tr>
                        <td>Content English:</td>
                        <td style="max-width: 100%">
                            <div class="content_info">
                                {!! $info->content_e !!}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Created at :</td>
                        <td> {{ $info->created_at }}</td>
                    </tr>
                    <tr>
                        <td>Uadated at :</td>
                        <td> {{ $info->updated_at }}</td>
                    </tr>
                    <tr>
                        <td>Deleted at :</td>
                        <td> {{ $info->deleted_at }}</td>
                    </tr>
                </table>
                <div class="right">
                    <a href='{{ url("/mn_info") }}' class="btn btn-danger">Quay lại</a>
                </div>
            </div>
        </div>
    </div>   
</article>
@endsection