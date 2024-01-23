@extends('layouts.admin.app')

@section('title')
Manage Info
@endsection

@section('content')
<article class="content responsive-tables-page">
    <div class="info">
        <div class="card">
            <h1 class="text-IBM">Danh sách thông tin</h1>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-block" id="info">
                            <div class="card-title-block" id="create">
                                <!-- show notification -->
                                @if(session('success'))
                                    <div class="alert alert-success" id="error-message">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                @if(session('error'))
                                    <div class="alert alert-danger" id="error-message">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                <!-- Create -->
                                <a class="d-left" href="{{ url('/mn_info/create') }}" style="display: block;">
                                    <img src="{{ asset('assets/img/icon-plus.png') }}" class="mw-45" style="vertical-align: middle">
                                    <span class="fz-24" style="vertical-align: middle">Thêm thông tin</span>
                                </a>

                                <!-- Check infos -->
                                @if (!empty($infos) && count($infos) != 0)
                                    <!-- Search -->
                                    <form method="GET" class="right">
                                        <div class="input-container">
                                            <input type="text" class="search"
                                                name="search" id="search"
                                                placeholder="Search by name, phone, email"
                                                value="<?php if (isset($_GET['search'])) { echo $_GET['search'];} ?>">
                                            <button type="submit" id="btn_search" name="btn_search" hidden></button>
                                            <div class="underline"></div>
                                        </div>
                                    </form>

                                    <!-- Select number on page -->
                                    <select name="results_per_page" id="results-per-page" aria-label="Số lượng kết quả trên mỗi trang" style="padding: 6px 0; vertical-align: middle; margin-top: 10px; margin-bottom: 5px;">
                                        <option value="5">Select the number of results in the page</option>
                                        <option value="5" {{ Request::get('results_per_page') == 5 ? 'selected' : '' }}>5</option>
                                        <option value="10" {{ Request::get('results_per_page') == 10 ? 'selected' : '' }}>10</option>
                                        <option value="30" {{ Request::get('results_per_page') == 30 ? 'selected' : '' }}>30</option>
                                    </select>
                                @endif
                                <!-- /Check infos -->
                            </div>

                            <!-- Check infos -->
                            @if (!empty($infos) && count($infos) != 0)
                            <section class="example">
                                <div class="table-responsive">
                                    <form>
                                        <table
                                            class="table table-bordered table-hover">
                                            <thead>
                                                <tr class="table-primary">
                                                    <!-- Check all -->
                                                    <th class="w5">ID</th>
                                                    <th class="w10">Type</th>
                                                    <th class="w15">Title</th>
                                                    <th class="w15">Title English</th>
                                                    <th class="w15">Chức Năng</th>
                                                </tr>
                                            </thead>

                                            @foreach($infos as $info)
                                            <tr>
                                                <!-- Check selected -->
                                                <td class="w5">
                                                    {{ $info->id }}</td>
                                                <td class="w10">{{
                                                    $info->type }}</td>
                                                <td class="w15">
                                                    <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; height: 2.7em; line-height: 1.35em; white-space: normal;">
                                                        {{ $info->title }}
                                                    </div>
                                                </td>
                                                <td class="w15">
                                                    <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; height: 2.7em; line-height: 1.35em; white-space: normal;">
                                                        {{ $info->title_e }}
                                                    </div>
                                                </td>
                                                <td class="w15">
                                                    <a
                                                        href='{{ url("/mn_info/{$info->id}") }}'>
                                                        <i class="fa fa-eye icon-view"></i>
                                                    </a>
                                                    <a
                                                        href='{{ url("/mn_info/{$info->id}/edit") }}'>
                                                        <i class="fa fa-pencil icon-edit"></i>
                                                    </a>
                                                    <a href="{{ url("/mn_info/$info->id/delete") }}"
                                                        onclick="return confirm('Bạn có chắc muốn xóa không?');">
                                                        <i class="fa fa-trash-o icon-delete"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </table>
                                        {{ $infos->links() }}
                                    </form>
                                </div>
                            </section>
                            @endif
                            <!-- /Check infos -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</article>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<!-- Get the quantity value for pagination -->
<script>
    document.getElementById('results-per-page').onchange = function () {
        var currentUrl = new URL(window.location.href);
        var searchParams = new URLSearchParams(currentUrl.search);

        searchParams.set('results_per_page', this.value);
        currentUrl.search = searchParams.toString();

        window.location.href = currentUrl.toString();
    };
</script>

<!-- Hiển thị và ẩn thông báo lỗi và thành công từng dòng lần lượt -->
<script>
    function showHideMessages() {
        // Ẩn thông báo lỗi sau 1 giây
        if (document.getElementById('error-message')) {
            setTimeout(function() {
                document.getElementById('error-message').style.display = 'none';
            }, 1000);
        }
    }           
    
    // Gọi hàm để bắt đầu hiển thị và ẩn thông báo
    showHideMessages();
</script>
@endsection