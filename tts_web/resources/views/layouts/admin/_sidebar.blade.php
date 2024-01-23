    <aside class="sidebar">
        <div class="sidebar-container">
            <div class="sidebar-header">
                <div class="brand">
                    <div class="logo">
                        <span class="l l1"></span>
                        <span class="l l2"></span>
                        <span class="l l3"></span>
                        <span class="l l4"></span>
                        <span class="l l5"></span>
                    </div>  
                    @if (Auth::check() && Auth::user()->role == 'admin') <span class="text-Patt"> {{ trans('messages.admin') }} </span> @endif
                    @if (Auth::check() && Auth::user()->role == 'user') <span class="text-Patt"> {{ trans('messages.student') }} </span> @endif
                </div>
            </div>
            <nav class="menu">
            @if (Auth::check() && Auth::user()->role == 'admin')
                <ul class="sidebar-menu metismenu" id="sidebar-menu">
                    <!-- Dashboard -->
                    <li class="{{ (request()->is('admin*')) ?  'active' : ''}}">
                        <a href="{{ url('/admin') }}" >
                            <i class="fa fa-home"></i> {{ trans('messages.dashboard') }}
                        </a>
                    </li>

                    <!-- Quản lý người dùng -->
                    <li class="{{ (request()->is('users/student*')) ?  'active' : ''}}">
                        <a href="{{ url("users/student") }}">
                            <i class="fa fa-group"></i> 
                            {{ trans('messages.manage_users') }}
                        </a>
                    </li>

                    <!-- Quản lý tin tức -->
                    <li class="{{ ( request()->is("news/auth*") || request()->is("news/mn_users*") ) ?  'active' : ''}}">
                        <a href="#">
                            <i class="fa fa-th-large"></i> {{ trans('messages.manage_news') }} <i class="fa arrow"></i>
                        </a>
                        <ul class="sidebar-nav">
                            <li>
                                <a href="{{ url("/news/auth") }}"> {{ trans('messages.manage_news_personal') }} </a>
                            </li>
                            <li>
                                <a href="{{ url("/news/mn_users") }}"> {{ trans('messages.manage_news_user') }} </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Quản lý liên hệ -->
                    <li class="{{ request()->is("contact*") ?  'active' : ''}}">
                        <a href=" {{ url("/contact") }} ">
                            <i class="fa fa-th-large"></i> {{ trans('messages.manage_contact') }}
                        </a>
                    </li>

                    <!-- Quản lý thông tin -->
                    <li class="{{ request()->is("mn_info*") ?  'active' : ''}}">
                        <a href=" {{ url("/mn_info") }} ">
                            <i class="fa fa-th-large"></i> {{ trans('messages.manage_info') }}
                        </a>
                    </li>
                </ul>
            @endif

            @if (Auth::check() && Auth::user()->role == 'user')
                <ul class="sidebar-menu metismenu" id="sidebar-menu">
                    <!-- Dashboard -->
                    <li class="{{ (request()->is('admin*')) ?  'active' : ''}}">
                        <a href="{{ url('/admin') }}">
                            <i class="fa fa-home"></i> {{ trans('messages.dashboard') }}
                        </a>
                    </li>

                    <!-- Quản lý tin tức -->
                    <li class="{{ (request()->is('news/auth*')) ?  'active' : ''}}">
                        <a href="{{ url("/news/auth") }}">
                            <i class="fa fa-th-large"></i> {{ trans('messages.manage_news_personal') }}
                        </a>
                    </li>
                </ul>
            @endif
            </nav>
        </div>
    </aside>

    <div class="sidebar-overlay" id="sidebar-overlay"></div>
    <div class="sidebar-mobile-menu-handle" id="sidebar-mobile-menu-handle"></div>
    <div class="mobile-menu-handle"></div>


