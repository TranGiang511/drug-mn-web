@extends('layouts.admin.app')

@section('title')
Quản lý tin tức người dùng
@endsection

@section('content')
<article class="content forms-page">
    <div class="card col-md-12" style="padding: 0px;">
        <h1 class="text-IBM">Xem bài viết</h1>
    </div>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="vietnam-tab" data-toggle="tab" href="#vietnam" role="tab" aria-controls="home" aria-selected="true">VietNam</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="english-tab" data-toggle="tab" href="#english" role="tab" aria-controls="profile" aria-selected="false">English</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ url("/news/mn_users") }}" role="tab">Back</a>
        </li>
      </ul>
      <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="vietnam" role="tabpanel" aria-labelledby="vietnam-tab">
            @if(Auth::check()) 
                @if(isset($new) && $new->id = $id) 
                    <!--Show post-->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-block ">
                                <!-- Show notification -->
                                <div class="mt-3">
                                    @if(session('error'))
                                        <div class="alert alert-danger" id="error-message">
                                            {{ session('error') }}
                                        </div>
                                    @endif
                                </div>
                                <!-- /Show notification -->
                                
                                <!-- Thumbnail -->
                                <div class="form-group">
                                    <div class="mb-3" style="font-weight: bold;">Thumbnail :</div>
                                    <div class="ml-5">
                                        <img class="mw-200px mb-4" src="{{ asset("/uploads/post/thumbnail/$new->thumbnail") }}">
                                    </div>
                                </div>

                                <!-- Id, name -->
                                <table class="table">
                                    <tr>
                                        <td class="w-25">ID :</td>
                                        <td> {{ $new->id }}</td>
                                    </tr>
                                    <tr>
                                        <td>User name :</td>
                                        <td>{{ $new->name }}</td>
                                    </tr>
                                </table>

                                <!-- Title -->
                                <div class="form-group">
                                    <label for="title">Title : </label>
                                    <div class="ml-5">{{ $new->title }}</div>
                                </div>

                                <!-- Content -->
                                <div class="form-group">
                                    <label for="title">Content : </label>
                                    <div class="ml-5">{!! $new->content !!}</div>
                                </div>

                                <table class="table">
                                    <tr>
                                        <td class="w-25">Created at :</td>
                                        <td> {{ $new->created_at }}</td>
                                    </tr>
                                    <tr>
                                        <td>Uadated at :</td>
                                        <td> {{ $new->updated_at }}</td>
                                    </tr>
                                    <tr>
                                        <td>Deleted at :</td>
                                        <td> {{ $new->deleted_at }}</td>
                                    </tr>
                                </table>
                                <div class="right">
                                    <a href="{{ url("/news/mn_users") }}" class="btn btn-primary">Back</a>
                                </div>
                            </div>
                        </div>
                    </div>   
                    <!-- /Show post -->
                @else 
                    <div class="card card-block">
                        <div class="alert-hi">Không tồn tại tin tức!</div>
                    </div>
                @endif
            @endif
        </div>
        <div class="tab-pane fade" id="english" role="tabpanel" aria-labelledby="english-tab">
            @if(Auth::check()) 
                @if(isset($new_tran) && $new_tran->id_news = $id) 
                    <!--Show post-->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-block ">
                                <!-- Show notification -->
                                <div class="mt-3">
                                    @if(session('error'))
                                        <div class="alert alert-danger" id="error-message">
                                            {{ session('error') }}
                                        </div>
                                    @endif
                                </div>
                                <!-- /Show notification -->

                                <!-- Thumbnail -->
                                <div class="form-group">
                                    <div class="mb-3" style="font-weight: bold;">Thumbnail :</div>
                                    <div class="ml-5">
                                        <img class="mw-200px mb-4" src="{{ asset("/uploads/post/thumbnail/$new_tran->thumbnail") }}">
                                    </div>
                                </div>

                                <!-- Id, name -->
                                <table class="table">
                                    <tr>
                                        <td class="w-25">ID :</td>
                                        <td> {{ $new_tran->id }}</td>
                                    </tr>
                                    <tr>
                                        <td>User name :</td>
                                        <td>{{ $new_tran->name }}</td>
                                    </tr>
                                </table>

                                <!-- Title -->
                                <div class="form-group">
                                    <label for="title">Title : </label>
                                    <div class="ml-5">{{ $new_tran->title }}</div>
                                </div>

                                <!-- Content -->
                                <div class="form-group">
                                    <label for="title">Content : </label>
                                    <div class="ml-5">{!! $new_tran->content !!}</div>
                                </div>

                                <table class="table">
                                    <tr>
                                        <td class="w-25">Created at :</td>
                                        <td> {{ $new_tran->created_at }}</td>
                                    </tr>
                                    <tr>
                                        <td>Uadated at :</td>
                                        <td> {{ $new_tran->updated_at }}</td>
                                    </tr>
                                    <tr>
                                        <td>Deleted at :</td>
                                        <td> {{ $new_tran->deleted_at }}</td>
                                    </tr>
                                </table>
                                <div class="right">
                                    <a href="{{ url("/news/mn_users") }}" class="btn btn-primary">Back</a>
                                </div>
                            </div>
                        </div>
                    </div>   
                    <!-- /Show post -->
                @else
                    <div class="card card-block">
                        <div class="alert-hi">There is no English translation of news yet!</div>
                    </div>
                @endif
            @endif
        </div>
      </div>
</article>

<!-- Show and hide error messages -->
<script>
    function showHideMessages() {
        // Ẩn thông báo lỗi sau 2 giây
        if (document.getElementById('error-message')) {
            setTimeout(function() {
                document.getElementById('error-message').style.display = 'none';
            }, 2000);
        }
    }
    showHideMessages();
</script>
@endsection