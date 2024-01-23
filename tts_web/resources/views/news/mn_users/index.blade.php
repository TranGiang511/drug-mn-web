@extends('layouts.admin.app')

@section('title')
Quản lý tin tức người dùng
@endsection

@section('content')
@if ($countPost <= 0)
    <article class="content forms-page">
        <div class="card card-block">
            <div class="alert-hi">Chưa có bài post người dùng nào</div>
        </div>
    </article>
@endif
@if ($countPost > 0)
    <article class="content responsive-tables-page">
        <div class="courses">
            <div class="card">
                <h1 class="text-IBM"> Danh sách tin tức người dùng </h1>
            </div>
            <section class="section">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-block">
                                <div class="card-title-block" id="create">
                                    <!-- show notification -->
                                    @if (session('success'))
                                        <div class="alert alert-success"  id="error-message">
                                            {{ session('success') }}
                                        </div>
                                    @endif

                                    @if ($errors->has('error_selected'))
                                        <div class="alert alert-danger" id="error-message">
                                            <strong>Error:</strong> {{ $errors->first('error_selected') }}
                                        </div>
                                    @endif

                                    <!-- Create news -->
                                    @if (Auth::user()->role == 'admin')
                                    <a class="d-left" href="{{ url('/news/create') }}" name="create"><img src="{{ asset('assets/img/icon-plus.png') }}" class="mw-45" alt=""><span class="fz-24">Thêm bài viết</span></a>
                                    @endif

                                    <!-- Search -->
                                    <form method="GET" class="right">
                                        <div class="input-container">
                                            <input type="text" class="search" name="search" placeholder="Search by title" value="<?php if (isset($_GET['search'])) {echo $_GET['search']; } ?>">
                                            <div class="underline"></div>
                                        </div>
                                    </form>

                                    @if (Auth::check() && Auth::user()->role == "admin")
                                    <!-- Delete Mutil -->
                                    <a href="javascript:void(0)" onclick="if (confirm('Bạn có chắc muốn xóa những bài viết đã chọn không?')) document.getElementById('new-delete-multi').submit()" class="btn btn-danger" style="display: inline-block; margin-top: 10px;">
                                        Delete Selected
                                    </a>
                                    <!--/ Delete Mutil-->
                                    @endif

                                    <!-- Banner Selected -->
                                    @if (Auth::check() && Auth::User()->role == "admin")
                                    <form action="{{ route('news_public_mutil') }}" method="POST" id="news_public_mutil" style="display:inline-block; vertical-align: bottom;">
                                        @csrf
                                        <button type="submit" class="btn btn-primary" id="btn_public_mutil">Public Selected</button>
                                        <input type="text" name="search_public" id="search-field-excel" value="{{ request('search') }}" hidden>
                                    </form>
                                    @endif
                                    <!--/ Banner Selected-->

                                    <!-- Select number on page -->
                                    <select name="results_per_page" id="results-per-page" aria-label="Số lượng kết quả trên mỗi trang" style="padding: 6px 0; vertical-align: middle; margin-top: 10px; margin-bottom: 5px;">
                                        <option value="5">Select the number of results in the page</option>
                                        <option value="5" {{ Request::get('results_per_page') == 5 ? 'selected' : '' }}>5</option>
                                        <option value="10" {{ Request::get('results_per_page') == 10 ? 'selected' : '' }}>10</option>
                                        <option value="15" {{ Request::get('results_per_page') == 15 ? 'selected' : '' }}>15</option>
                                    </select>
                                    <!-- /Select number on page -->
                                </div>

                                <section class="example">
                                    @if (Auth::user()->role == 'admin')
                                    <form method="POST" id="new-delete-multi" action="{{ url('/newsDeleteMutilUsers') }}">
                                        @csrf
                                        @method('DELETE')
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover">
                                                <thead>
                                                    <tr class="table-primary">
                                                        <td class="w5">
                                                            <input type="checkbox" name="check_all" id="check_all">
                                                        </td>
                                                        <th class="w5">ID</th>
                                                        <th class="w10">Tác giả</th>
                                                        <th class="w10">Thumbnail</th>
                                                        <th class="w20">Tiêu đề</th>
                                                        <th class="w20">Nội dung</th>
                                                        <th class="w15">Tình Trạng</th>
                                                        <th class="w20">Chức Năng</th>
                                                    </tr>
                                                </thead>
                                                @foreach($news_users as $new_user)
                                                <tr>
                                                    <td class="w5">
                                                        <input type="checkbox" name="check_item[{{ $new_user->id }}]" value="{{ $new_user->id }}" id="check_item">
                                                    </td>
                                                    <td class="w5">{{ $new_user->id }}</td>
                                                    <td class="w10">{{ $new_user->name }}</td>
                                                    <td class="w10">
                                                        <img class="img" src="{{ asset("uploads/post/thumbnail/$new_user->thumbnail") }}" style="object-fit: cover; width: 60px; height: 60px; ">
                                                    </td>
                                                    <td class="w20">
                                                        <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; height: 2.7em; line-height: 1.35em; white-space: normal;">
                                                            {{ $new_user->title }}
                                                        </div>
                                                    </td>
                                                    <td class="w20">
                                                        <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; height: 2.7em; line-height: 1.35em; white-space: normal;">
                                                            {!! $new_user->content !!}
                                                        </div>
                                                    </td>
                                                    <td class="w15">
                                                        <?php switch ($new_user->public_check) {
                                                            case '0':
                                                                echo 'Chờ phê duyệt';
                                                                break;
                                                            case '1':
                                                                echo 'Đã phê duyệt';
                                                                break;
                                                        } ?>
                                                    </td>
                                                    <td class="w20">
                                                        <a href="{{ url("/news/mn_users/$new_user->id/show/") }}">
                                                            <i class="fa fa-eye icon-view"></i>
                                                        </a>
                                                        <a href="{{ url("/news/delete/$new_user->id") }}" onclick="return confirm('Bạn có chắc muốn xóa không?');">
                                                            <i class="fa fa-trash-o icon-delete"></i>
                                                        </a>
                                                        <a href="{{ url("/news/public/$new_user->id") }}" onclick="return confirm('Bạn có chắc muốn public không?');" class="text-primary">
                                                            Public
                                                        </a>                                            
                                                    </tr>
                                                @endforeach
                                            </table>
                                            {{ $news_users->links('') }}
                                        </div>
                                    </form>
                                    @endif
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </article>
@endif

<!-- Get the selected item -->
<script>
    const checkAll = document.getElementById("check_all");
    const items = document.querySelectorAll("input[type='checkbox']");

    checkAll.addEventListener("change", function () {
        items.forEach(item => {
            item.checked = checkAll.checked;
        });
        console.log(items);
    });
</script>

<!-- Show and hide error messages -->
<script>
    function showHideMessages() {
        // Ẩn thông báo lỗi sau 1 giây
        if (document.getElementById('error-message')) {
            setTimeout(function() {
                document.getElementById('error-message').style.display = 'none';
            }, 1000);
        }
    }
    showHideMessages();
</script>

<!-- Get the quantity value for pagination -->
<script>
    document.getElementById('results-per-page').onchange = function () {
        window.location = "{{ route('news.mn_users.index') }}?results_per_page=" + this.value;
    };
</script>

<!-- Get checkbox send form public mutil form -->
<script>
    document.getElementById("btn_public_mutil").addEventListener("click", function() {
        var selectedCheckboxes = document.querySelectorAll('input[type="checkbox"][name^="check_item"]:checked');
        
        // Checkbox được chọn, thêm các giá trị vào form
        var form = document.getElementById('news_public_mutil');
        var inputsInForm = form.querySelectorAll('input[type="hidden"][name^="check_item"]');
        inputsInForm.forEach(input => {
            // Xóa các input ẩn cũ
            input.remove(); 
        });
        selectedCheckboxes.forEach(selectedCheckbox => {
            var hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = selectedCheckbox.name;
            hiddenInput.value = selectedCheckbox.value;
            form.appendChild(hiddenInput);
        });
    });
</script>
@endsection