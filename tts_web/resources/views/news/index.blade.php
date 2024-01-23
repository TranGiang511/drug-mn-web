@extends('layouts.forum.app')

@section('content')
<style>
    .tt-item .tt-col-avatar img {
        object-fit: cover; 
        width: 45px; 
        height: 45px; 
    }
    @media (max-width: 450px) {
        .tt-item .tt-col-avatar img {
            object-fit: cover; 
            width: 40px; 
            height: 40px; 
        }
    }
</style>

<main id="tt-pageContent" class="bg-main" >
    <div class="container">
        <!-- No posts -->
        @if ($countPost === 0)
            <p style="font-weight: bold; text-align: left;">No translated posts available.</p>
        @endif
        @if ($locale === 'vi' && $countPost === 0)
            <p style="font-weight: bold; text-align: left;">Không có bài viết bản dịch này.</p>
        @endif
        <!-- /No posts -->
        
        @if ($countPost > 0)
        <div class="mx-auto d-flex row" style="justify-content: space-between; align-items: baseline;">
            <!-- Link -->
            <div class="link">
                <a href="{{url('/home')}}">{{ trans('messages.home') }} <span style="color:rgba(102, 104, 104, 0.655)">/</span></a>
                <a href="{{url('/news')}}" style="color: rgb(55, 55, 241);">{{ trans('messages.news') }}</a>
            </div>
            <!-- /Link -->

            <!-- search -->
            <form method="GET" >
                <div class="input-group">
                    <input type="text" class="form-control mr-3 search" name="search" value="<?php if (isset($_GET['search'])) { echo $_GET['search'];} ?>" placeholder="Search by new title" class="search">
                </div>
            </form>
            <!-- /search -->
        </div>
        @endif 
        <!-- index new -->
        <div class="tt-topic-list">
            <div class="tt-topic-alert tt-alert-default mt_10px" role="alert">
                Xin chào ! Đây là <a href="#" target="_blank"> diễn đàn của Vinicorp</a> . Hãy cùng chúng tôi chia sẻ những kiến thức bổ ích nào!
            </div>
            @if ($countPost <= 0)
            <div class="tt-topic-alert tt-alert-default mt-3" role="alert">
                Diễn đàn là nơi Chia sẻ những kinh nghiệm hay về việc học ngoại ngữ, chọn trường/ trung tâm ngoại ngữ chất lượng . Các chủ đề xoay quay việc học ngoài ngữ . Khi post bài đề nghị post đúng mục và chủ đề .. tránh tình trạng spam ...
            </div>
            <div class="tt-topic-alert tt-alert-default mt-3 mb-5" role="alert">
                Hãy tạo mới news đầu tiên để chia sẻ kinh nghiệm của mình đến tất cả học viên, giảng viên của trung tâm Vinicorp chúng tôi.
            </div>
            @endif

            @if ($countPost > 0)
                <form method="POST" id="new-delete-multi" action="{{ url('/newsDeleteMutil') }}" style="">
                    @csrf
                    @method('DELETE')
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        {{-- @if (Auth::check() && Auth::user()->role == "admin")
                            <input type="checkbox" name="check_all" id="check_all" style="margin: 15px;">
                            
                            <!-- Delete Mutil -->
                            <a href="javascript:void(0)" onclick="if (confirm('Bạn có chắc muốn xóa những bài viết đã chọn không?')) document.getElementById('new-delete-multi').submit()" class="btn btn-danger" style="display: inline-block; margin-top: 10px;">
                                Delete Selected
                            </a>
                            <!--/ Delete Mutil-->
                        @endif --}}

                        <!-- Select number on page -->
                        <select name="results_per_page" class="form-select" id="results-per-page" aria-label="Số lượng kết quả trên mỗi trang" style="margin-left: auto; margin-top: 20px;">
                            <option value="5">Select the number of results in the page</option>
                            <option value="5" {{ Request::get('results_per_page') == 5 ? 'selected' : '' }}>5</option>
                            <option value="10" {{ Request::get('results_per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="15" {{ Request::get('results_per_page') == 15 ? 'selected' : '' }}>15</option>
                        </select>
                        <!-- /Select number on page -->
                    </div>

                    <!-- Show notification -->
                    <div class="mt-3">
                        @if(session('error'))
                            <div class="alert alert-danger" id="error-message">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success" id="error-message">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('messsage'))
                            <div class="alert alert-success" id="error-message">
                                {{ session('messsage') }}
                            </div>
                        @endif

                        {{-- @if ($errors->has('error_selected'))
                            <div class="alert alert-danger" id="error-message">
                                <strong>Error:</strong> {{ $errors->first('error_selected') }}
                            </div>
                        @endif --}}

                        @if ($errors->has('error_permission'))
                            <div class="alert alert-danger" id="error-message">
                                <strong>Error:</strong> {{ $errors->first('error_permission') }}
                            </div>
                        @endif
                    </div>
                    <!-- /Show notification -->

                    @if (isset($news))
                    @foreach ($news as $new)
                    <div class="tt-item @if ($new->role == "admin")  tt-itemselect  @endif ">
                        {{-- <!-- check permission delete mutil -->
                        <div style="width: 17px;">
                            @if (Auth::check() && ((Auth::user()->role == 'admin' && ($new->id_user == Auth::user()->id || $new->role != 'admin'))))
                                <input type="checkbox" name="check_item[{{ $new->id }}]" value="{{ $new->id }}" id="check_item">
                            @endif
                        </div>
                        <!-- /check permission delete mutil --> --}}

                        <div class="tt-col-avatar">
                            <img class="img" width="40" src="{{ asset("uploads/post/thumbnail/$new->thumbnail") }}">
                        </div>
                        <div class="tt-col-description">
                            <h6 class="tt-title">
                                {{ $new->name }}
                            </h6>
                            <div class="row align-items-center no-gutters">
                                <div class="col-12">
                                    @if ($locale === 'vi')
                                        <a href="{{ url('/news/' . $new->id) }}" class="text-dark">
                                            <div class="word-break" style="max-width: 100%; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; height: 2.7em; line-height: 1.35em; white-space: normal;">{{ $new->title }}</div>
                                        </a>
                                    @elseif ($locale === 'en')
                                        <a href="{{ url('/news/' . $new->id_news) }}" class="text-dark">
                                            <div class="word-break" style="max-width: 100%; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; height: 2.7em; line-height: 1.35em; white-space: normal;">{{ $new->title }}</div>
                                        </a>
                                    @endif
                                </div>
                                <div class="col-3 ml-auto show-mobile">
                                    <div class="tt-value size_10px">{{ date('d M Y', strtotime($new->public_date))  }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="tt-col-value hide-mobile size_14px">{{ date('d M Y', strtotime($new->public_date))  }}</div>
                        <div class="tt-col-value hide-mobile size_14px">{{ date('H : i', strtotime($new->public_date)) }}</div>

                        @php
                            $existingTranslation = DB::table('news_tran')->where('id_news', $new->id)->first();
                            $locale = session('locale');
                        @endphp
                        <div class="col size_14px">
                             <!-- admin có thể chỉnh bài viết của mk và người dùng, hoặc nếu bài viết là của người dùng đang đăng nhập. -->
                            @if (Auth::check() && ((Auth::user()->role == 'admin' && ($new->id_user == Auth::user()->id || $new->role != 'admin')) || ($new->role != 'admin' && $new->id_user == Auth::user()->id)))
                                @if ($locale == 'vi' && !$existingTranslation)
                                    <a href="{{ url("/news/create_en/$new->id") }}" class="btn btn-primary">
                                        + En News
                                    </a>  
                                @endif
                            @endif

                            {{-- @if (($locale == 'vi' && $existingTranslation) || $locale == 'en')
                                <div class="size_14px">
                                    <a href="{{ url("/news/$new->id/self_edit") }}" class="btn btn-primary">
                                        Edit En News
                                    </a>  
                                </div>
                            @endif --}}
                            {{-- <h2>Test</h2> --}}
                        </div>
                    </div>
                    @endforeach

                    <div class="mb-2 mt-5">
                        <div class="d-flex justify-content-around row">
                            {{ $news->links() }}
                        </div>
                    </div>
                    @endif
                </form>
            @endif
        </div>
        <!-- /index post -->
    </div>
</main>

{{-- <!-- Get the selected item -->
<script>
    const checkAll = document.getElementById("check_all");
    const items = document.querySelectorAll("input[type='checkbox']");

    checkAll.addEventListener("change", function () {
        items.forEach(item => {
            item.checked = checkAll.checked;
        });
        console.log(items);
    });
</script> --}}

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

<!-- Show and hide error messages -->
<script>
    function showHideMessages() {
        // Ẩn thông báo lỗi sau 5 giây
        if (document.getElementById('error-message')) {
            setTimeout(function() {
                document.getElementById('error-message').style.display = 'none';
            }, 5000);
        }
    }
    showHideMessages();
</script>
@endsection