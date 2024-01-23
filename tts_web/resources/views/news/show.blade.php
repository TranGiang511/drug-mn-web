@extends('layouts.forum.app')

@section('content')
<main id="tt-pageContent" class="bg-main mt-3">
    <div class="container">
        <!-- Show notification --->
        @if ($new == null)
            <div name="item">
                <div class="tt-item card-block mb-5 p-1" >
                    @if ($locale == 'en')
                        <div style="font-weight: bold; text-align: center;">There is no English translation of news yet!</div>
                    @elseif ($locale == 'vi')
                        <div style="font-weight: bold; text-align: center;">Không tồn tại tin tức!</div>
                    @else
                        <div style="font-weight: bold; text-align: center;">There is no translation of news yet!</div>
                    @endif
                </div>
            </div>
        @endif
        <!-- /Show notification --->

        @if ($new != null)
        <div class="tt-single-topic-list">
            <!-- Link -->
            <div class="link">
                <a href="{{url('/home')}}">Trang chủ <span style="color:rgba(102, 104, 104, 0.655)">/</span></a>
                <a href="{{url('/news')}}">Diễn đàn <span style="color:rgba(102, 104, 104, 0.655)">/</span></a>
                <span style="color: rgb(55, 55, 241);">{{$new->title}}</span>
            </div>
            <!-- /Link -->

            <!-- Show error -->
            @if(session('error'))
                <div class="tt-topic-list" style="margin: 20px 0 5px;">
                    <div class="tt-topic-alert tt-alert-default" role="alert" id="error-message">
                        {{ session('error') }}
                    </div>
                </div>
            @endif
            <!-- /Show error -->

            <!--Show post-->

            <div class="tt-item card card-block">
                <div class="tt-single-topic">
                    <div class="tt-item-header">
                        <div class="tt-item-info info-top">
                            <div class="tt-avatar-icon">
                                <img class="img" width="50" src="{{ asset('/uploads/post/thumbnail/'.$new->thumbnail) }}" style="object-fit: cover; width: 60px; height: 60px;">
                            </div>
                            <div class="tt-avatar-title">
                                {{ $new->name }}
                            </div>
                            <div class="tt-info-time">
                                {{ $new->created_at }}
                            </div>
                        </div>
                        <div class="reponsive">
                            {{ $new->title }}
                        </div>
                    </div>
                    <div class="tt-item-description reponsive table-responsive">
                        {!! $new->content !!}
                    </div>
                </div>

                @if (Auth::check())
                <div class="row justify-content-space-evenly" id="a_post">
                    <!-- create Comment -->
                    <a href="#reply" class="tt-icon-btn">
                        <img src="{{ asset('assets/img/forum/comments.png') }}" class="icon_post">
                        <span class="reply">5</span>
                    </a>

                    <!-- Edit post -->
                    @if ($locale == 'vi')
                        @php $id = $new->id; @endphp
                    @elseif ($locale == 'en')
                        @php $id = $new->id_news; @endphp
                    @endif
                    
                    @if ($new->id_user == Auth::user()->id )
                    <a href="{{ url("/news/$id/self_edit") }}" class="tt-icon-btn">
                        <img src="{{ asset('assets/img/forum/edit.png') }}" class="icon_post">
                    </a>
                    @endif

                    <!-- Delete post -->
                    @if (($new->id_user == Auth::user()->id) || (Auth::user()->role == 'admin') )
                    <a href="{{ url("/news/delete/$id") }}" onclick="return confirm('Bạn có chắc muốn xóa không?');" class="tt-icon-btn">
                        <img src="{{ asset('assets/img/forum/delete.png') }}" class="icon_post">
                    </a>
                    @endif

                    <!-- Report post -->
                    <a href="#" class="tt-icon-btn">
                        <img src="{{ asset('assets/img/forum/warn.png') }}" class="icon_post">
                        <span class="report">0</span>
                    </a>
                </div>
                @endif
            </div>
            <!-- /Show post -->

            <!-- Show Comment -->
            <!-- Viết nội dung comment tại đây -->
            <!-- /Show Comment -->
        </div>
        
        <!-- Create comment -->
        @if (Auth::check())
        <form method="POST" action="">
            {{-- @csrf --}}
            <div class="pt-editor form-default" id="reply">
                <h5 class="pt-title reply">Post Your Reply</h5>

                <div class="form-group">
                    <textarea class="summernoteContentComment" name="content">
                    {{-- {{ old('content') }} --}}
                    </textarea>
                    @error('content')
                        <div class="form-text text-danger">
                            {{-- {{ $message }} --}}
                        </div>
                    @enderror
                </div>

                <div class="row form-group">
                    <div class="col-auto ml-md-auto">
                        <button class="btn btn-primary btn-width-lg" type="submit">Reply</button>
                    </div>
                </div>
            </div>
        </form>
        @endif
        <!-- /Create comment -->
        @endif
    </div>
</main>

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