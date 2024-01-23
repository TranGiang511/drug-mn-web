<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Forum</title>
    <meta name="description" content="Forum">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Exo:ital,wght@0,400;0,500;1,500&family=IBM+Plex+Serif:ital,wght@0,300;0,400;1,300&family=Noto+Serif+JP:wght@300;400;500&family=Pattaya&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    
    <link rel="stylesheet" id="blue-style" href="{{ asset('css/app-blue.css') }}">
    <link rel="stylesheet"  href="{{ asset('css/vendor.css') }}">
    <link rel="stylesheet" href="{{ asset('css/forum.css') }}" >

    <!-- css language -->
    <style>
        .language-switcher {
            display: flex;
            align-items: center;
        }

        .flag-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            margin: 0 5px;
        }

        .flag-btn img {
            width: 30px;
            height: auto;
        }

        #mobile-menu ul li a {
            text-transform: capitalize;
        }

        .language-dropdown img {
            width: 28px;
            padding-right: 10px;
        }

        .language-dropdown .dropdown-item {
            font-size: 15px;
        }

        .tt-user-info img {
            width: 30px;
        }

        .tt-user-info .name {
            width: 60px; 
            white-space: nowrap; 
            overflow: hidden;
            text-overflow: ellipsis; 
        }
    </style>

    <!-- Own style css -->
    @yield('own_style')
</head>
<body>
<!-- tt-mobile menu -->
<nav class="panel-menu" id="mobile-menu">
    <ul>

    </ul>
    <div class="mm-navbtn-names">
        <div class="mm-closebtn">
            {{ trans('messages.close') }}
            <div class="tt-icon">
                <svg>
                  <use xlink:href="#icon-cancel"></use>
                </svg>
            </div>
        </div>
        <div class="mm-backbtn">{{ trans('messages.back') }}</div>
    </div>
</nav>
<!-- /tt-mobile menu -->
<header id="tt-header">
    <div class="container">
        <div class="row tt-row">
            <div class="col-auto">
                <!-- toggle mobile menu -->
                <a class="toggle-mobile-menu" href="#">
                    <svg class="tt-icon text-white">
                      <use xlink:href="#icon-menu_icon"></use>
                    </svg>
                </a>
                <!-- /toggle mobile menu -->
                <!-- logo -->
                <a href="{{ url('/news') }}" class="mr-lg-5">
                    <div class="tt-logo" style="vertical-align: middle;">
                        Vini
                    </div>
                </a>
                <!-- /logo -->
                <!-- Desktop menu -->
                <div class="tt-desktop-menu">
                    <nav>
                        <ul class="font">
                            <!-- Home -->
                            <li>
                                <a href="{{ url('/home') }}" class="{{ (request()->is('home*')) ? 'active' : '' }}">
                                    {{ trans('messages.home') }}
                                </a>
                            </li>

                            <!-- Lịch biểu -->
                            <li>
                                <a href="{{ url('/calendar') }}" class="{{ (request()->is('calendar*')) ? 'active' : '' }}">
                                    {{ trans('messages.calendar') }}
                                </a>
                            </li>

                            <!-- Tin tức -->
                            <li>
                                <a href="{{ url('/news') }}" class="{{ (request()->is('news*')) ? 'active' : '' }}">
                                    {{ trans('messages.news') }}
                                </a>
                            </li>

                            <!-- Thông tin -->
                            @php $locale = session('locale', config('app.locale')); @endphp
                            @if (isset($infos))
                                @foreach ($infos as $info)
                                    <li>
                                        @if ($locale == 'en')
                                            <a href="{{ route('info.type', ['infoType' => $info->type]) }}" class="{{ (request()->is("info/$info->type*")) ?  'active' : ''}}">
                                            {{ $info->title_e }}
                                            </a>
                                        @else 
                                            <a href="{{ route('info.type', ['infoType' => $info->type]) }}" class="{{ (request()->is("info/$info->type*")) ?  'active' : ''}}">
                                            {{ $info->title }}
                                            </a>
                                        @endif
                                    </li>
                                @endforeach
                            @endif

                            @if (Auth::check())
                                <!-- User -->
                                <li>
                                    <a href="{{ url('/admin') }}">
                                        @if (Auth::user()->role == "admin")  {{ trans('messages.admin') }} @endif 
                                        @if (Auth::user()->role == "user")  {{ trans('messages.student') }} @endif 
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
                <!-- /Desktop menu -->
            </div>

            <div class="col-auto ml-auto">
                @if (Auth::check())
                <!-- User=>profile + logout -->
                <div class="tt-user-info d-flex justify-content-center dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center p-3" data-toggle="dropdown" href="{{ url("/admin") }}" role="button" aria-haspopup="true" aria-expanded="false">
                        <div class="d-flex align-items-center">
                            <img class="rounded-circle img" width="30px" src="{{ asset("/uploads/avatars/defaultFemale.jpg") }}" style="margin-right: 5px;">
                            <span class="name text-white"> {{ Auth::user()->name }} </span>
                        </div>
                    </a>  
                    <div class="dropdown-menu profile-dropdown-menu bg-blue" aria-labelledby="dropdownMenu1">
                      <a class="dropdown-item text-white mb-2" href="{{ url("/users/self_show") }} ">
                        <i class="fa fa-user icon"></i> {{ trans('messages.profile') }} </a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item text-white" href="{{url('/logout')}}">
                        <i class="fa fa-power-off icon"></i> {{ trans('messages.logout') }} </a>
                    </div>
                </div>
                <!-- /User=>profile + logout -->   
                @endif

                @if (!Auth::check())
                <!-- Login -->
                <div class="col-auto ml-auto">
                    <div class="tt-user-info d-flex justify-content-center">
                        <div class="tt-account-btn">
                            <a href="{{ url('/login') }}"> 
                              <span class="text-white" style="text-transform: uppercase; font-size: 16px; font-weight: 500; letter-spacing: 0.01em;
                            }">{{ trans('messages.login') }}</span> 
                            </a>
                        </div>
                    </div>
                </div>
                <!--/ Login -->
                @endif

                <!-- Language -->
                <div class="language-dropdown">
                    <a class="dropdown-toggle" href="{{ route('setLanguage', session('locale', config('app.locale'))) }}" role="button" data-toggle="dropdown" aria-expanded="false" id="languageDropdown" style="color: white;">
                        @php
                            // Kiểm tra nếu không có ngôn ngữ nào được lưu trong session, thiết lập ngôn ngữ mặc định là vi
                            $locale = session('locale', 'vi');

                            // Lưu ngôn ngữ vào session để sử dụng sau này
                            session(['locale' => $locale]);
                            if ($locale === 'en') {
                                echo 'EN';
                            } elseif ($locale === 'vi') {
                                echo 'VN';
                            } elseif ($locale === 'jp') {
                                echo 'JP';
                            } else {
                                echo 'VN';
                            }
                        @endphp
                    </a>
                    <div class="dropdown-menu" aria-labelledby="languageDropdown" style="min-width: auto;">
                        <a class="dropdown-item" href="{{ route('setLanguage', 'en') }}">
                            <img src="{{ asset('/assets/img/lang/en.png') }}" alt="icon_en"> English
                        </a>
                        <a class="dropdown-item" href="{{ route('setLanguage', 'vi') }}">
                            <img src="{{ asset('/assets/img/lang/vn.png') }}" alt="icon_vn"> Tiếng Việt
                        </a>
                        <a class="dropdown-item" href="{{ route('setLanguage', 'jp') }}">
                            <img src="{{ asset('/assets/img/lang/jp.png') }}" alt="icon_jp"> 日本語
                        </a>
                    </div>
                </div>                
                <!-- /Language -->
            </div>
        </div>
    </div>
</header>

<!--create new-->
@if (Auth::check())
<a href="{{ url('/news/create') }}" class="tt-btn-create-topic p-2">
    <span class="tt-icon">
        <svg>
          <use xlink:href="#icon-create_new"></use>
        </svg>
    </span>
</a>
@endif
<svg width="0" height="0" class="hidden">
	<symbol xmlns="http://www.w3.org/2000/svg" viewBox="0 0 161.51 161.51" id="icon-create_new"><circle cx="80.76" cy="80.76" r="80.76" fill="#e1c34e"></circle><path d="M80.76 121.26c-2.49 0-4.5-2.01-4.5-4.5v-72a4.5 4.5 0 0 1 9 0v72c0 2.48-2.02 4.5-4.5 4.5z"></path><path d="M116.76 85.26h-72a4.5 4.5 0 0 1 0-9h72a4.5 4.5 0 0 1 0 9z"></path></symbol>
</svg>