@extends('layouts.home.app')

@section('title')
Calendar
@endsection

@section('content')
<section class="section mb-4" style="margin-top: 130px;">
    <div class="container">
        <!-- Link -->
        <div class="mx-auto d-flex row mb-4" style="justify-content: space-between; align-items: baseline;">
            <div class="link">
                <a href="{{url('/home')}}">{{ trans('messages.home') }} <span style="color:rgba(102, 104, 104, 0.655)">/</span></a>
                <a href="{{ url('/calendar') }}" style="color: rgb(55, 55, 241);">{{ trans('messages.calendar') }}</a>
            </div>
        </div>
        <!-- /Link -->

        <div id="calendar"></div>
    </div>
</section>

<script src="{{ asset('js/calendar-cdn.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var locale = '{{ session('locale') }}';
        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: locale,
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next,today,dayGridMonth,timeGridWeek,timeGridDay',
                right: 'title',
            },
            buttonText: {
                today: locale == 'en' ? 'Today' : 'Hôm nay',
                month: locale == 'en' ? 'Month' : 'Tháng',
                week: locale == 'en' ? 'Week' : 'Tuần',
                day: locale == 'en' ? 'Day' : 'Ngày',
            },
            firstDay: 1,
            events: [
                @if ($locale == 'en')
                    @if (isset($postsByDate))
                        @foreach($postsByDate as $post)
                            {
                                title: window.innerWidth > 767 ? "{{ $post->count }} posts" : "{{ $post->count }}", // Số bài viết
                                start: '{{ $post->date }}', // Ngày
                            },
                        @endforeach
                    @endif
                @else 
                    @if (isset($postsByDate))
                        @foreach($postsByDate as $post)
                            {
                                title: window.innerWidth > 767 ? "{{ $post->count }} bài đăng" : "{{ $post->count }}", // Số bài viết
                                start: '{{ $post->date }}', // Ngày
                            },
                        @endforeach
                    @endif
                @endif
            ],
            dateClick: function(info) {
                // Lấy ngày được click
                var clickedDate = info.date;
                var newDate = new Date(clickedDate.getFullYear(), clickedDate.getMonth(), clickedDate.getDate() + 1);
                // Chuyển đổi ngày sang định dạng YYYY-MM-DD để sử dụng trong URL
                var formattedDate = newDate.toISOString().slice(0, 10);
                var url = '/calendar-show/' + formattedDate;
                // Chuyển hướng đến trang với danh sách bài viết của ngày đã chọn
                window.location.href = url;
            }
        });
    
        calendar.render();
    
        // Xử lý responsive cho headerToolbar với hai dòng
        function handleHeaderToolbar() {
            var toolbar = document.querySelector('.fc-toolbar');
            var header = document.querySelector('.fc-header-toolbar');
    
            if (window.innerWidth <= 767) {
                header.style.display = 'grid';
                toolbar.style.gridTemplateRows = 'auto auto';
                header.style.margin = '0 auto 20px';
    
                // Di chuyển các nút bên trái và bên phải lên hàng đầu tiên
                document.querySelector('.fc-prev-button').style.gridRow = '1 / span 1';
                document.querySelector('.fc-next-button').style.gridRow = '1 / span 1';
                document.querySelector('.fc-today-button').style.gridRow = '1 / span 1';
                document.querySelector('.fc-dayGridMonth-button').style.gridRow = '1 / span 1';
                document.querySelector('.fc-timeGridWeek-button').style.gridRow = '1 / span 1';
                document.querySelector('.fc-timeGridDay-button').style.gridRow = '1 / span 1';
    
                // Đặt tiêu đề ở hàng thứ hai
                var title = document.querySelector('.fc-toolbar-title');
                title.style.gridRow = '2 / span 1';
                title.style.margin = 'auto 0'; 
                title.style.textAlign = 'center';
            } else {
                header.style.display = '';
                toolbar.style.gridTemplateRows = '';
                header.style.margin = '';
    
                // Đặt lại vị trí các nút khi kích thước màn hình lớn hơn 767 pixel
                document.querySelectorAll('.fc-button').forEach(function(btn) {
                    btn.style.gridRow = '';
                });
                title.style.margin = ''; 
                title.style.textAlign = '';
            }
        }
    
        window.addEventListener('resize', handleHeaderToolbar);
        handleHeaderToolbar(); // Gọi hàm này lúc ban đầu
    });
</script>

@endsection