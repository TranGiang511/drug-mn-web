@extends('layouts.forum.app')

@section('content')
    @if ($locale == 'vi')
        @php $id = $new->id; @endphp
    @elseif ($locale == 'en')
        @php $id = $new->id_news; @endphp
    @endif

    @if(Auth::check()) 
        @if(!isset($new)) 
            <main id="tt-pageContent" class="bg-main">
                <div class="container">
                    <div class="tt-topic-list">
                        <div class="tt-topic-alert tt-alert-default mt_10px" role="alert">
                            There are no translations of this article to edit!
                        </div> 
                    </div>
                </div>
            </main>
        @else
            @if($new->id_user == Auth::user()->id)
                <!-- Edit post -->
                <form  method="POST" action="{{url("/news/$id/self_edit")}}" enctype="multipart/form-data" id="form-news-edit">
                    @csrf
                    @method('PUT')
                    <main id="tt-pageContent" class="bg-main">
                        <div class="container">
                            <div class="title-block">
                                <h3 class="title"> 
                                    Edit Post <span class="sparkline bar" data-type="bar"></span>
                                </h3>
                            </div>
                            <div name="item">
                                <!-- Show notification -->
                                <div class="mt-3">
                                    @if(session('error'))
                                        <div class="alert alert-danger" id="error-message">
                                            {{ session('error') }}
                                        </div>
                                    @endif
                                </div>
                                <!-- /Show notification -->
    
                                <div class="card card-block">
                                    <!-- User Name -->
                                    <div class="form-group row mb-5">
                                        <div class="col-sm-2 text-xs-right">
                                        </div>
                                        <div class="col-sm-10 mt-3">
                                            <h4> {{ Auth::user()->name }} </h4>
                                        </div>
                                    </div>
    
                                    <!-- Title -->
                                    <div class="form-group row">
                                        <label class="col-sm-2 form-control-label text-xs-right"> Title: </label>
                                        <div class="col-sm-10">
                                            <input type="text" name="title" class="form-control" value="{{ old('title', $new->title) }}" required id="title">
                                            <div class="form-text text-danger" id="error_title"></div>
                                            @error('title')
                                                <div class="form-text text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
    
                                    <!-- Thumbnail -->
                                    <div class="form-group row">
                                        <label class="col-sm-2 form-control-label text-xs-right"> Thumbnail: </label>
                                        <div class="col-sm-10">
                                            <input type="file" name="thumbnail" id="thumbnail" id="thumbnail">
                                            <div class="form-text text-danger" id="error_thumbnail"></div>
                                            <img class="img" width="50" src="{{ asset("/uploads/post/thumbnail/".$new->thumbnail) }}" style="object-fit: cover; width: 60px; height: 60px;">
                                            @error('thumbnail')
                                                <div class="form-text text-danger">{{ $message }}</div>
                                            @enderror 
                                        </div>
                                    </div>
    
                                    <!-- Content -->
                                    <div class="form-group row">
                                        <label class="col-sm-2 form-control-label text-xs-right"> Content: </label>
                                        <div class="col-sm-10">
                                            <textarea class="summernoteContent" name="content" id="content" required> 
                                                {!! old('content', $new->content) !!}
                                            </textarea>
                                            <div class="form-text text-danger" id="error_content"></div>
                                            @error('content')
                                                <div class="form-text text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
    
                                    <!-- Button -->
                                    <div class="row form-group">
                                        <div class="col-auto ml-md-auto">
                                            <button class="btn btn-primary btn-width-lg" type="submit">Edit</button>
                                            <a href="{{ url("/news/$new->id") }}" class="btn btn-secondary mr-3">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </main>
                </form>
            @endif
        @endif
    @endif

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