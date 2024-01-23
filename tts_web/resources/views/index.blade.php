@extends('layouts.home.app')

@section('title')
Home
@endsection

@section('content')
<!-- CSS -->
<style>
    @media (max-width: 767px) {
      #banner_title_default {
        font-size: 16px;
      }
    }
    @media (max-width: 400px) {
      #banner_title_default {
        font-size: 14px;
      }
    }
    @media (max-width: 375px) {
      #carouselExampleIndicators .custom-carousel-caption {
          top: 50%;
          padding: 15px 10px;
      }
    }
</style>
<!-- /CSS -->

<!-- Banner/Slider -->
<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" style="margin: 55px 0 50px;">
  <div class="carousel-inner">
    @if (isset($count_news_banner) && $count_news_banner > 0) 
      <!-- Default -->
      <div class="carousel-item active">
        <img class="d-block w-100" src="{{ asset('assets/img/home/news_banner.jpg') }}" alt="First slide">
        <div class="carousel-caption custom-carousel-caption carousel-default">
          <h2 id="banner_title_default">{{ trans('messages.welcome_title') }}</h2>
          <p class="d-none d-md-block d-sm-none">{{ trans('messages.welcome_description') }}</p>
        </div>
      </div>
      <!-- /Default -->

      <!-- DB_banner -->
      @foreach ($news_banner as $new_banner )

        <!-- Set id when select locale -->
        @if ($locale == 'vi')
          @php $id_new_banner = $new_banner->id; @endphp
        @elseif ($locale == 'en')
          @php $id_new_banner = $new_banner->id_news; @endphp
        @endif
        <!-- /Set id when select locale -->

        <div class="carousel-item">
          <img class="d-block w-100" src="{{ asset('/uploads/post/thumbnail/'.$new_banner->thumbnail) }}" alt="slide">
          <div class="carousel-caption custom-carousel-caption">
            <p class="card-title" style="max-width: 100%; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; height: 2.7em; line-height: 1.35em; white-space: normal;">{{ $new_banner->title }}</p>
            <a href="{{ url("/news/$id_new_banner") }}" class="btn btn-primary">{{ trans('messages.details') }}</a>
          </div>
        </div>
      @endforeach
      <!-- /DB_banner -->
    @else
      <!-- Default -->
      <div class="carousel-item active">
        <img class="d-block w-100" src="{{ asset('assets/img/home/news_banner.jpg') }}" alt="First slide">
        <div class="carousel-caption custom-carousel-caption d-none d-md-block carousel-default">
          <h2>{{ trans('messages.welcome_title') }}</h2>
          <p>{{ trans('messages.no_highlight_news') }}</p>
        </div>
      </div>
      <!-- /Default -->
    @endif
  </div>
  @if (isset($count_news_banner) && $count_news_banner > 0) 
    <a class="w3-button w3-black w3-display-left" href="#carouselExampleIndicators" role="button" data-slide="prev">&#10094;</a>
    <a class="w3-button w3-black w3-display-right" href="#carouselExampleIndicators" role="button" data-slide="next">&#10095;</a>
  @endif
</div>
<!-- /Banner/Slider -->

<!-- News -->
<section id="news" class="section">
  <div class="container">
    <div class="row">

      <div class="col-md-12 text-center">
        <div class="section-title" data-aos="fade-in" data-aos-delay="100">
          <h2 style="text-transform: uppercase;">{{ trans('messages.news') }}</h2>
          <h4>{{ trans('messages.section_news_title') }}</h4>
        </div>
      </div>

      @if (isset($countPost) && $countPost > 0) 
      <div class="col-md-12">
        <div class="featured-carousel owl-carousel mb-4">
          @foreach ($news as $new_item)
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
                    <h5 class="card-title" style="font-weight: bold; max-width: 100%; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; height: 2.7em; line-height: 1.35em; white-space: normal;"> {{ $new_item->title }} </h5>
                    <div class="card-content" style="max-width: 100%; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; height: 2.7em; line-height: 1.35em; white-space: normal;">{!! $new_item->content !!}</div>
                    <a href="{{ url("/news/$id_new_item") }}" class="btn btn-primary" style="background-color: #5AB9EA">{{ trans('messages.view_details') }}</a>
                  </div>
                </div>
              </div>
          @endforeach
        </div>

        <div class="text-center mb-4">
          <p>{{ trans('messages.section_news_description') }}</p>
          <a href="{{ url("/news") }}" class="btn btn-primary">{{ trans('messages.view_more') }}</a>
        </div>
      </div>
      @else 
      <div class="col-md-12">
        <div class="text-center mb-4">
          <p>{{ trans('messages.no_news') }}</p>
          <a href="@if(Auth::check()){{ url("/news/create") }} @else {{ url("/login") }} @endif " class="btn btn-primary">{{ trans('messages.create_more') }}</a>
        </div>
      </div>
      @endif

    </div>
  </div>
</section>
<!--/ News  -->

<!-- Contact Start -->
<div class="container-xxl py-5" id="contact">
  <div class="container">
      <div class="col-md-12 text-center">
        <div class="section-title" data-aos="fade-in" data-aos-delay="100">
          <h2 style="text-transform: uppercase;">{{ trans('messages.contact_title') }}</h2>
          <h4>{{ trans('messages.contact_description') }}</h4>
        </div>
      </div>
      <div class="row info-contact" data-aos="fade-up" data-aos-delay="100">
        <div class="col-lg-6">
          <div class="info-box mb-4">
            {{-- <i class="bx bx-map"></i> --}}
            <h3>Our Address</h3>
            <p>T 14, Toà ICON4 TOWER, 243A La Thành, Láng Thượng, Đống Đa.</p>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="info-box  mb-4">
            {{-- <i class="bx bx-envelope"></i> --}}
            <h3>Email Us</h3>
            <p>info@vinicorp.com.vn</p>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="info-box  mb-4">
            {{-- <i class="bx bx-phone-call"></i> --}}
            <h3>Call Us</h3>
            <p>(+84)-24.3556.3607</p>
          </div>
        </div>
      </div>
      <div class="row g-4">
          <div class="col-md-6 wow fadeIn" data-wow-delay="0.1s">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.1234954720844!2d105.80185617397181!3d21.027744087815993!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ac9f07337671%3A0x3738d875d489a9a5!2sVINICORP%20-%20Viet%20Nhat%20General%20JSC!5e0!3m2!1svi!2sus!4v1699597752190!5m2!1svi!2sus" frameborder="0" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
          </div>
          <div class="col-md-6">
              <div class="wow fadeInUp" data-wow-delay="0.2s">
                  <form id="form-contact" action="{{ route('contact_submit') }}" method="POST">
                      @csrf
                      @if(session('success_contact'))
                        <div id="success_contact" class="alert alert-warning">
                          {{ session('success_contact') }}
                        </div>
                      @endif
                      <div class="row g-3">
                          <div class="col-md-6">
                              <div class="form-floating mb-3">
                                  <label for="name">Your Name</label>
                                  <input type="text" class="form-control" id="name" placeholder="Your Name" name="name" value="{{ old('name') }}" required>
                                  <small id="error_name" class="text-warning"></small>
                                  @error('name')
                                      <small id="error_name" class="text-warning">
                                        {{ $message }}
                                      </small>
                                  @enderror
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="form-floating mb-3">
                                  <label for="email">Your Email</label>
                                  <input type="email" class="form-control" id="email" placeholder="Your Email" name="email" value="{{ old('email') }}" required>
                                  <small id="error_email" class="text-warning"></small>
                                  @error('email')
                                      <small id="error_email" class="text-warning">
                                            {{ $message }}
                                      </small>
                                  @enderror
                              </div>
                          </div>
                          <div class="col-12">
                              <div class="form-floating mb-3">
                                  <label for="phone">Your phone</label>
                                  <input type="number" class="form-control" id="phone" placeholder="Your phone" name="phone" value="{{ old('phone') }}" required>
                                  <small id="error_phone" class="text-warning"></small>
                                  @error('phone')
                                      <small id="error_phone" class="text-warning">
                                          {{ $message }}
                                      </small>
                                  @enderror
                              </div>
                          </div>
                          <div class="col-12">
                            <div class="form-floating mb-3">
                                <label for="title">Your title</label>
                                <input type="title" class="form-control" id="title" placeholder="Your title" name="title" value="{{ old('title') }}" required>
                                <small id="error_title" class="text-warning"></small>
                                @error('title')
                                    <small id="error_title" class="text-warning">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                          </div>
                          <div class="col-12">
                              <div class="form-floating mb-3">
                                  <label for="message">Message</label>
                                  <textarea class="form-control" placeholder="Leave a message here" id="message" style="height: 150px" name="message" required>{{ old('message') }}</textarea>
                                  <small id="error_message" class="text-warning"></small>
                                  @error('message')
                                      <small id="error_message" class="text-warning">
                                          {{ $message }}
                                      </small>
                                  @enderror
                              </div>
                          </div>

                          <!-- Google reCaptcha -->
                          <div class="col-12 mb-3">
                            <div class="g-recaptcha" name="g-recaptcha-response" id="feedback-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                            <small id="error_g-recaptcha-response" class="text-warning"></small>
                            @error('g-recaptcha-response')
                              <small id="error_g-recaptcha-response" class="text-warning"></small>
                            @enderror
                          </div>
                          
                          <div class="col-12">
                              <button class="btn btn-primary w-100" type="submit">Send Message</button>
                          </div>
                      </div>
                      <div id="result" class="text-warning mt-3"></div>
                  </form>
              </div>
          </div>
      </div>
  </div>
</div>
<!-- Contact End -->

<!-- Ẩn thông báo sau 10s -->
<script>
  if (document.getElementById('success_contact')) {
      setTimeout(function() {
          document.getElementById('success_contact').style.display = 'none';
      }, 10000);
  }
</script>

<!-- Recaptcha -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection
