@extends('layouts.home.app')

@section('title')
    Info | {{ $infoOfType->title }}
@endsection

@section('content')
<style>
    .show_content_info img {
        max-width: 100%;
    }
</style>
<section class="section mb-4" style="margin-top: 130px;">
    <div class="container">
        <!-- Link -->
        <div class="mx-auto d-flex row mb-4" style="justify-content: space-between; align-items: baseline;">
            <div class="link">
                <a href="{{url('/home')}}">{{ trans('messages.home') }} <span style="color:rgba(102, 104, 104, 0.655)">/</span></a>
                @if ($locale == 'en')
                    <a href="{{ route('info.type', ['infoType' => $infoOfType->type]) }}" style="color: rgb(55, 55, 241);">{{ $infoOfType->title_e }}</a>
                @else 
                    <a href="{{ route('info.type', ['infoType' => $infoOfType->type]) }}" style="color: rgb(55, 55, 241);">{{ $infoOfType->title }}</a>
                @endif
            </div>
        </div>
        <!-- /Link -->
        @if ($locale == 'en')
            <div style="max-width:100%;" class="show_content_info">
                {!! $infoOfType->content_e !!}
            </div>
        @else 
            <div style="max-width:100%;" class="show_content_info">
                {!! $infoOfType->content !!}
            </div>
        @endif
    </div>
</section>
@endsection