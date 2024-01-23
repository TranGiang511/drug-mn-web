@extends('layouts.admin.app')

@section('title')
Manage Contact
@endsection

@section('content')
<article class="content responsive-tables-page">
    <div class="contact">
        <div class="card">
            <h1 class="text-IBM">Danh sách liên hệ</h1>
        </div>

        <!-- Check contacts -->
        @if (!empty($contacts) && count($contacts) == 0)
            <div class="card card-block">
                <div class="alert-hi">Chưa có liên hệ nào!</div>
            </div>
        @else
            <section class="section">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-block" id="contact">
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

                                    @if ($errors->has('error_selected'))
                                        <div class="alert alert-danger" id="error-message">
                                            <strong>Error:</strong> {{ $errors->first('error_selected') }}
                                        </div>
                                    @endif

                                    <!-- Check contacts -->
                                    @if (!empty($contacts) && count($contacts) != 0)
                                        <!-- Delete Mutil -->
                                        <a href="javascript:void(0)"
                                            onclick="if (confirm('Bạn có chắc muốn xóa liên hệ đã chọn không?')) document.getElementById('contact-delete-multi').submit()"
                                            class="btn btn-danger" style="display: inline-block; margin-top: 10px;">
                                            Delete Selected
                                        </a>

                                        <!-- Select number on page -->
                                        <select name="results_per_page" id="results-per-page" aria-label="Số lượng kết quả trên mỗi trang" style="padding: 6px 0; vertical-align: middle; margin-top: 10px; margin-bottom: 5px;">
                                            <option value="5">Select the number of results in the page</option>
                                            <option value="5" {{ Request::get('results_per_page') == 5 ? 'selected' : '' }}>5</option>
                                            <option value="10" {{ Request::get('results_per_page') == 10 ? 'selected' : '' }}>10</option>
                                            <option value="30" {{ Request::get('results_per_page') == 30 ? 'selected' : '' }}>30</option>
                                        </select>

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
                                    @endif
                                    <!-- /Check contacts -->
                                </div>

                                <!-- Check contacts -->
                                @if (!empty($contacts) && count($contacts) != 0)
                                    <section class="example">
                                        <div class="table-responsive">
                                            <form method="POST" id="contact-delete-multi"
                                                action="{{ url('/contactDeleteMutil') }}">
                                                @csrf
                                                @method('DELETE')
                                                <table
                                                    class="table table-bordered table-hover">
                                                    <thead>
                                                        <tr class="table-primary">
                                                            <!-- Check all -->
                                                            <th class="w5">
                                                                <input type="checkbox"
                                                                    name="check_all"
                                                                    id="check_all">
                                                            </th>
                                                            <th class="w5">ID</th>
                                                            <th class="w10">Họ Tên</th>
                                                            <th class="w10">Email</th>
                                                            <th class="w10">SĐT</th>
                                                            <th class="w15">Tiêu đề</th>
                                                            <th class="w20">Nội dung</th>
                                                            <th class="w15">Trạng thái</th>
                                                            <th class="w10">Chức Năng</th>
                                                        </tr>
                                                    </thead>

                                                    @foreach($contacts as $contact)
                                                    <tr>
                                                        <!-- Check selected -->
                                                        <td class="w5">
                                                            <input type="checkbox"
                                                                name="check_item[{{$contact->id}}]"
                                                                value="{{$contact->id}}"
                                                                id="check_item">
                                                        </td>
                                                        <td class="w5">
                                                            {{ $contact->id }}</td>
                                                        <td class="w10">{{
                                                            $contact->name }}</td>
                                                        <td class="w10">{{
                                                            $contact->email }}</td>
                                                        <td class="w10">{{
                                                            $contact->phone }}</td>
                                                        <td class="w15">
                                                            <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; height: 2.7em; line-height: 1.35em; white-space: normal;">
                                                                {{ $contact->title }}
                                                            </div>
                                                        </td>
                                                        <td class="w20">
                                                            <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; height: 2.7em; line-height: 1.35em; white-space: normal;">
                                                                {{ $contact->content }}
                                                            </div>
                                                        </td>
                                                        <td class="w15">
                                                            @if(Auth::User()->role == 'admin')
                                                                @if($contact->viewing_status == 0)
                                                                    <span style="color: red;">Chưa xem</span>
                                                                @else 
                                                                    <span style="color: rgb(26, 5, 250);">Đã xem</span>
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td class="w10">
                                                            <a
                                                                href='{{ url("/contact/{$contact->id}") }}'>
                                                                <i class="fa fa-eye icon-view"></i>
                                                            </a>
                                                            <a href="{{ url("/contact/delete/$contact->id") }}"
                                                                onclick="return confirm('Bạn có chắc muốn xóa không?');">
                                                                <i class="fa fa-trash-o icon-delete"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </table>
                                                {{ $contacts->links() }}
                                            </form>
                                        </div>
                                    </section>
                                @endif
                                <!-- /Check contacts -->
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif
        <!-- /Check contacts -->
    </div>
</article>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<!-- Get the selected item -->
<script>
    const checkAll = document.getElementById("check_all");
    const items = document.querySelectorAll("input[type='checkbox']");

    checkAll.addEventListener("change", function () {
        items.forEach(item => {
            item.checked = checkAll.checked;
        });
    });
</script>

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