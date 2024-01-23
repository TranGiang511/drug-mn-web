@extends('layouts.admin.app')

@section('title')
Quản lý tin tức cá nhân
@endsection

@section('content')
<article class="content forms-page">
    <div class="card col-md-12" style="padding: 0px;">
        <h1 class="text-IBM">Sửa bài viết</h1>
    </div>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="vietnam-tab" data-toggle="tab" href="#vietnam" role="tab" aria-controls="home" aria-selected="true">VietNam</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="english-tab" data-toggle="tab" href="#english" role="tab" aria-controls="profile" aria-selected="false">English</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ url("/news/auth") }}" role="tab">Back</a>
        </li>
      </ul>
      <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="vietnam" role="tabpanel" aria-labelledby="vietnam-tab">
            @if(Auth::check()) 
                @if(isset($new)) 
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-block sameheight-item">
                                <form action="{{ url("/news_auth/$id/update_vi") }}" method="POST" enctype="multipart/form-data">
                                    @method('PUT')
                                    @csrf

                                    <!-- Title -->
                                    <h3 class="mt-4 text-center">Update VietNam Post</h3>

                                    <!-- Show notification -->
                                    <div class="mt-3">
                                        @if(session('error'))
                                            <div class="alert alert-danger" id="error-message">
                                                {{ session('error') }}
                                            </div>
                                        @endif
                                    </div>
                                    <!-- /Show notification -->

                                    <!-- User Name -->
                                    <div class="form-group">
                                        <h4> {{ Auth::user()->name }} </h4>
                                    </div>

                                    <!-- Thumbnail -->
                                    <div class="form-group">
                                        <label for="thumbnail">Thumbnail: </label>
                                        <div>
                                            <img src="{{ asset("/uploads/post/thumbnail/$new->thumbnail") }}" class="img-fluid limit-img">
                                        </div>
                                        <input type="file" name="thumbnail" value="{{ $new->thumbnail }}" id="thumbnail">
                                    </div>
                                    <div class="form-text text-danger" id="error_thumbnail"></div>
                                    @error('thumbnail')
                                        <div class="form-text text-danger">{{ $message }}</div>
                                    @enderror

                                    <!-- Title -->
                                    <div class="form-group">
                                        <label for="title">Title: </label>
                                        <input type="text" name="title" class="form-control" value="{{ old('title', $new->title) }}" id="title">
                                    </div>
                                    <div class="form-text text-danger" id="error_title"></div>
                                    @error('title')
                                        <div class="form-text text-danger">{{ $message }}</div>
                                    @enderror

                                    <!-- Public_date -->
                                    <div class="form-group">
                                        <label for="public_date"> Public Date: </label>
                                        <input type="datetime-local" name="public_date" class="form-control" style="max-width: 400px;" value="{{ old('public_date', $new->public_date) }}" min="{{ \Carbon\Carbon::parse($new->public_date)->format('Y-m-d') }}" id="public_date">
                                    </div>
                                    <div class="form-text text-danger" id="error_public_date"></div>
                                    @error('public_date')
                                        <div class="form-text text-danger">{{ $message }}</div>
                                    @enderror

                                    <!-- Content -->
                                    <div class="form-group">
                                        <label for="contentUpdateVN">Content: </label>
                                        <textarea class="form-control" name="content" id="contentUpdateVN" class="content" required> 
                                            {!! old('content', $new->content) !!}
                                        </textarea>
                                    </div>
                                    <div class="form-text text-danger" id="error_content"></div>
                                    @error('content')
                                        <div class="form-text text-danger">{{ $message }}</div>
                                    @enderror
                                    
                                    <!-- Banner: Show out slider_home -->
                                    @if(Auth::user()->role == "admin") 
                                        <div class="form-group">
                                            <label for="banner"> Is it displayed on the home banner? </label>
                                            <input type="checkbox" name="banner" {{ $new->banner == 1 ? 'checked' : ''}} id="banner">
                                        </div>
                                    @endif
                                    <div class="form-text text-danger" id="error_banner"></div>
                                    @error('banner')
                                        <div class="form-text text-danger">{{ $message }}</div>
                                    @enderror
                                        

                                    <!-- Submit -->
                                    <div style="text-align: right;">
                                        <button type="submit" class="btn btn-primary">Lưu thông tin</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
        <div class="tab-pane fade" id="english" role="tabpanel" aria-labelledby="english-tab">
            @if(Auth::check()) 
                @if(isset($new_tran) && $new_tran->id_news == $id)
                    <!-- Update new_tran -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-block sameheight-item">
                                <form action="{{ url("/news_auth/$id/update_en") }}" method="POST" enctype="multipart/form-data">
                                    @method('PUT')
                                    @csrf

                                    <!-- Title -->
                                    <h3 class="mt-4 text-center">Update English Post</h3>

                                    <!-- Show notification -->
                                    <div class="mt-3">
                                        @if(session('error'))
                                            <div class="alert alert-danger" id="error-message">
                                                {{ session('error') }}
                                            </div>
                                        @endif
                                    </div>
                                    <!-- /Show notification -->

                                    <!-- User Name -->
                                    <div class="form-group">
                                        <h4> {{ Auth::user()->name }} </h4>
                                    </div>

                                    <!-- Thumbnail -->
                                    <div class="form-group">
                                        <label for="thumbnail">Thumbnail: </label>
                                        <div>
                                            <img src="{{ asset("/uploads/post/thumbnail/$new_tran->thumbnail") }}" class="img-fluid limit-img">
                                        </div>
                                        <input type="file" name="thumbnail" value="{{ $new_tran->thumbnail }}" id="thumbnail">
                                    </div>
                                    <div class="form-text text-danger" id="error_thumbnail"></div>
                                    @error('thumbnail')
                                        <div class="form-text text-danger">{{ $message }}</div>
                                    @enderror

                                    <!-- Title -->
                                    <div class="form-group">
                                        <label for="title">Title: </label>
                                        <input type="text" name="title" class="form-control" value="{{ old('title', $new_tran->title) }}" id="title">
                                    </div>
                                    <div class="form-text text-danger" id="error_title"></div>
                                    @error('title')
                                        <div class="form-text text-danger">{{ $message }}</div>
                                    @enderror

                                    <!-- Public_date -->
                                    <div class="form-group">
                                        <label for="public_date"> Public Date: </label>
                                        <input type="datetime-local" name="public_date" class="form-control" style="max-width: 400px;" required value="{{ old('public_date', $new_tran->public_date) }}" min="<?php echo date('Y-m-d', strtotime($new_tran->public_date)); ?>" id="public_date">
                                    </div>
                                    <div class="form-text text-danger" id="error_public_date"></div>
                                    @error('public_date')
                                        <div class="form-text text-danger">{{ $message }}</div>
                                    @enderror

                                    <!-- Content -->
                                    <div class="form-group">
                                        <label for="contentUpdateEN">Content: </label>
                                        <textarea class="form-control" name="content" id="contentUpdateEN" required> 
                                            {!! old('content', $new_tran->content) !!}
                                        </textarea>
                                    </div>
                                    <div class="form-text text-danger" id="error_content"></div>
                                    @error('content')
                                        <div class="form-text text-danger">{{ $message }}</div>
                                    @enderror
                                    
                                    <!-- Banner: Show out slider_home -->
                                    @if(Auth::user()->role == "admin") 
                                        <div class="form-group">
                                            <label for="banner"> Is it displayed on the home banner? </label>
                                            <input type="checkbox" name="banner" {{ $new_tran->banner == 1 ? 'checked' : ''}} id="banner">
                                            @error('banner')
                                                <div class="form-text text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endif

                                    <!-- Submit -->
                                    <div style="text-align: right;">
                                        <button type="submit" class="btn btn-primary">Lưu thông tin</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                     <!-- /Update new_tran -->
                @else 
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-block sameheight-item">
                            <!-- create new_tran -->
                            <form method="POST" action="{{ url("/news_auth/$id/create_en") }}" enctype="multipart/form-data" id="form-news">
                                @csrf

                                <!-- Title -->
                                <h3 class="mt-4 text-center">Create A New English Post</h3>

                                <!-- Show notification -->
                                <div class="mt-3">
                                    @if(session('error'))
                                        <div class="alert alert-danger" id="error-message">
                                            {{ session('error') }}
                                        </div>
                                    @endif
                                </div>
                                <!-- /Show notification -->

                                <!-- User Name -->
                                <div class="form-group">
                                    <h4> {{ Auth::user()->name }} </h4>
                                </div>

                                <!-- Id_Post -->
                                <div class="form-group">
                                    <label> Vietnamese post id: </label>
                                    <span style="font-weight: bold;">
                                        {{ $id }}
                                    </span>
                                </div>
                            
                                <!-- Language Selection -->
                                <div class="form-group">
                                    <label> Language: English</label>
                                </div>

                                <!-- Title -->
                                <div class="form-group">
                                    <label for="title">Title: </label>
                                    <input type="text" name="title" class="form-control" required value="{{ old('title') }}" id="title">
                                </div>
                                <div class="form-text text-danger" id="error_title"></div>
                                @error('title')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror

                                <!-- Thumbnail -->
                                <div class="form-group">
                                    <label for="thumbnail"> Thumbnail: </label>
                                    <input type="file" name="thumbnail" id="thumbnail" value="{{ old('thumbnail') }}" required>
                                </div>
                                <div class="form-text text-danger" id="error_thumbnail"></div>
                                @if(old('thumbnail'))
                                    <p class="text-success">File đã được chọn: {{ old('thumbnail') }}</p>
                                @endif
                                @error('thumbnail')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror 
                                
                                <!-- Public Date -->
                                <div class="form-group">
                                    <label for="public_date"> Public Date: </label>
                                    <input type="datetime-local" name="public_date" class="form-control" style="max-width: 400px;" required value="{{ old('public_date') }}" min="{{ now()->format('Y-m-d') }}" id="public_date">
                                </div>
                                <div class="form-text text-danger" id="error_public_date"></div>
                                @error('public_date')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror

                                <!-- Content -->
                                <div class="form-group">
                                    <label for="contentCreateEn"> Content: </label>
                                    <textarea name="content" class="form-control" id="contentCreateEn" required>
                                        {!! old('content') !!}
                                    </textarea>
                                </div>
                                <div class="form-text text-danger" id="error_content"></div>
                                @error('content')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror

                                <!-- Banner: Show out slider_home -->
                                @if(Auth::user()->role == "admin") 
                                    <div class="form-group">
                                        <label for="banner"> Is it displayed on the home banner? </label>
                                        <input type="checkbox" name="banner" value="1" id="banner">
                                    </div>
                                    @error('banner')
                                        <div class="form-text text-danger">{{ $message }}</div>
                                    @enderror
                                @endif
                                
                                <!-- Button -->
                                <div class="row form-group">
                                    <div class="col-auto ml-md-auto">
                                        <button class="btn btn-primary btn-sm" type="submit">Create Post</button>
                                        <a class="btn btn-danger btn-sm ml-2" href="{{ url("/news/auth") }}">Cancel</a>
                                    </div>
                                </div>
                            </form>
                            <!-- /create new_tran -->
                        </div>
                    </div>
                @endif
            @endif
        </div>
      </div>
</article>

<script>
    const input = document.querySelector('input[type="file"]')

    function handleFiles(files) {
        console.log(files)
        const reader = new FileReader()
        reader.onload = function() {
            const img = document.querySelector('#img')
            img.src = reader.result
        }
        reader.readAsDataURL(files[0])
    }

    input.addEventListener('change', function(e) {
        handleFiles(input.files);
    })
</script>

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

<!-- ckeditor -->
<script src="{{ asset('plugin/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('plugin/ckfinder/ckfinder.js') }}"></script>
<script>
    createCkeditor('contentUpdateVN');
    createCkeditor('contentCreateEn');
    function createCkeditor(name) {
        CKEDITOR.replace(name, {
            filebrowserBrowseUrl: "{{ asset('plugin/ckfinder/ckfinder.html') }}",
            filebrowserImageBrowseUrl: "{{ asset('plugin/ckfinder/ckfinder.html?type=Images') }}",
            filebrowserFlashBrowseUrl: "{{ asset('plugin/ckfinder/ckfinder.html?type=Flash') }}",
            filebrowserUploadUrl: "{{ asset('plugin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files') }}",
            filebrowserImageUploadUrl: "{{ asset('plugin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images') }}",
            filebrowserFlashUploadUrl: "{{ asset('plugin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash') }}",
        });
    };
</script>
<script>
    createCkeditor('contentUpdateEN');
    function createCkeditor(name) {
        CKEDITOR.replace(name, {
            filebrowserBrowseUrl: "{{ asset('plugin/ckfinder/ckfinder.html') }}",
            filebrowserImageBrowseUrl: "{{ asset('plugin/ckfinder/ckfinder.html?type=Images') }}",
            filebrowserFlashBrowseUrl: "{{ asset('plugin/ckfinder/ckfinder.html?type=Flash') }}",
            filebrowserUploadUrl: "{{ asset('plugin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files') }}",
            filebrowserImageUploadUrl: "{{ asset('plugin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images') }}",
            filebrowserFlashUploadUrl: "{{ asset('plugin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash') }}",
        });
    };
</script>
@endsection