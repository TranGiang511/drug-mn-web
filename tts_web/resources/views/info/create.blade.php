
@extends('layouts.admin.app')

@section('title')
Manage Info
@endsection

@section('content')
<article class="content forms-page">
    <div class="card">
        <h1 class="text-IBM">Thêm thông tin</h1>
    </div>
    <div class="row sameheight-container">
        <div class="col-md-12">
            <div class="card card-block">
                <form action="{{ url("/mn_info/create") }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Title -->
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" class="form-control" placeholder="Title" aria-describedby="helpId">
                    </div>
                    @error('title')
                        <div class="form-text text-danger mb-3">{{ $message }}</div>
                    @enderror

                    <!-- Content -->
                    <div class="form-group">
                        <label for="content">Content</label>
                        <textarea name="content" class="form-control" id="content" required placeholder="Content" aria-describedby="helpId">
                            {!! old('content') !!}
                        </textarea>
                    </div>
                    @error('content')
                        <div class="form-text text-danger mb-3">{{ $message }}</div>
                    @enderror

                    <!-- Title_e -->
                    <div class="form-group">
                        <label for="title_e">Title English</label>
                        <input type="text" name="title_e" id="title_e" value="{{ old('title_e') }}" class="form-control" placeholder="Title English" aria-describedby="helpId">
                    </div>
                    @error('title_e')
                        <div class="form-text text-danger mb-3">{{ $message }}</div>
                    @enderror

                    <!-- Content_e -->
                    <div class="form-group">
                        <label for="content">Content English</label>
                        <textarea name="content_e" class="form-control" id="content_e" required placeholder="Content English" aria-describedby="helpId">
                            {!! old('content_e') !!}
                        </textarea>
                    </div>
                    @error('content_e')
                        <div class="form-text text-danger mb-3">{{ $message }}</div>
                    @enderror

                    <!-- Button -->
                    <div class="right">
                        <button type="submit" class="btn btn-primary" name="save">Lưu thông tin</button>
                        <a href='{{ url("/mn_info") }}' class="btn btn-danger">Quay lại</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</article>

<!-- ckeditor -->
<script src="{{ asset('plugin/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('plugin/ckfinder/ckfinder.js') }}"></script>
<script>
    createCkeditor('content');
    createCkeditor('content_e');
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