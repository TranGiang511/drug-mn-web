@extends('layouts.home.app')

@section('title')
Calendar show
@endsection

@section('content')
<!-- News -->
<section id="news" class="section" style="margin-top: 120px;">
  <div class="container">
    <div class="row">
      <div class="col-md-12 text-center">
        <div class="section-title" data-aos="fade-in" data-aos-delay="100">
          @if ($formattedDate)
            <h2>BÀI VIẾT NGÀY {{ $formattedDate }}</h2>
          @endif
          <h4>Các bài viết mới nhất, thông tin và sự kiện được chia sẻ tại ViNiCorp. Tất cả được tạo ra với mục tiêu phục vụ học viên và được cập nhật thường xuyên để thể hiện những thay đổi quan trọng trong kỳ thi IELTS.</h4>
        </div>
      </div>

      @if (isset($countPost) && $countPost > 0) 
      <div class="col-md-12">
        <div class="featured-carousel owl-carousel mb-4">
          @foreach ($news_date as $new_item)
              <!-- Set id when select locale -->
              @if ($locale == 'vi')
                @php $id_new_item = $new_item->id; @endphp
              @elseif ($locale == 'en')
                @php $id_new_item = $new_item->id_news; @endphp
              @endif
              <!-- /Set id when select locale -->

              <div class="item card" data-aos="fade-up" data-aos-delay="100">
                <div class="work">
                <img  style="height: 250px;" src="{{ asset('/uploads/post/thumbnail/'.$new_item->thumbnail) }}">
                  <div class="card-body" style=" display: flex; flex-direction: column;align-items: center; justify-content: space-between;">
                    <h5 class="card-title" style="font-weight: bold;max-width: 100%; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; height: 2.7em; line-height: 1.35em; white-space: normal;"> {{ $new_item->title }} </h5>
                    <div class="card-content" style="max-width: 100%; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; height: 2.7em; line-height: 1.35em; white-space: normal;">{!! $new_item->content !!}</div>
                    <a href="{{ url("/news/$id_new_item") }}" class="btn btn-primary" style="background-color: #5AB9EA">Xem chi tiết</a>
                  </div>
                </div>
              </div>
          @endforeach
        </div>

        <div class="text-center mb-4" style="margin: 50px 0;">
          {{ $news_date->links() }}
        </div>
      </div>
      @else 
      <div class="col-md-12">
        <div class="text-center mb-4">
          <p>Chưa có tin tức chia sẻ. Hãy cùng chúng tôi trải nghiệm ứng dụng, tạo thêm tin tức chia sẻ đến mọi người</p>
          <a href="@if(Auth::check()){{ url("/news/create") }} @else {{ url("/login") }} @endif " class="btn btn-primary">Tạo thêm</a>
        </div>
      </div>
      @endif

    </div>
  </div>
</section>
<!--/ News  -->
@endsection
