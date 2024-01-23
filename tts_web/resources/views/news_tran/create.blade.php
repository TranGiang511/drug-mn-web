@extends('layouts.forum.app')

@section('own_style')
<style>
    .radio-inline {
        margin-right: 30px;
    }
</style>
@endsection

@section('content')
    @if(Auth::check())
        <!-- create new new -->
        <form method="POST" action="{{ url("/news/store_en/$id_news") }}" enctype="multipart/form-data" id="form-news">
            @csrf
            <main id="tt-pageContent" class="bg-main">
                <div class="container">
                    <div class="title-block">
                        <h3 class="title"> 
                            Create A New English Post <span class="sparkline bar" data-type="bar"></span>
                        </h3>
                    </div>
                    <div name="item">
                        <div class="card card-block">
                            <!-- UserName -->
                            <div class="form-group row mb-5">
                                <div class="col-sm-2 text-xs-right">
                                </div>
                                <div class="col-sm-10 mt-3">
                                    <h4> {{ Auth::user()->name }} </h4>
                                </div>
                            </div>

                            <!-- Id_Post -->
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label text-xs-right"> Vietnamese post id: </label>
                                <div class="col-sm-10" style="font-weight: bold;">
                                    {{ $id_news }}
                                </div>
                            </div>
                        
                            <!-- Language Selection -->
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label text-xs-right"> Language: </label>
                                <div class="col-sm-10">
                                    <label class="radio-inline">
                                        <input type="radio" name="lang" value="en" checked> English
                                    </label>
                                    <div class="form-text text-danger" id="lang_error" ></div>
                                    @if ($errors->has('lang'))
                                        <div class="form-text text-danger" id="error-message">
                                            {{ $errors->first('lang') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Title -->
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label text-xs-right"> Title: </label>
                                <div class="col-sm-10">
                                    <input type="text" name="title" class="form-control" required value="{{ old('title') }}" id="title">
                                    <div class="form-text text-danger" id="error_title"></div>
                                    @error('title')
                                        <div class="form-text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Thumbnail -->
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label text-xs-right"> Thumbnail: </label>
                                <div class="col-sm-10">
                                    <input type="file" name="thumbnail" id="thumbnail" required>
                                    <div class="form-text text-danger" id="error_thumbnail"></div>
                                    @if(old('thumbnail'))
                                        <p class="text-success">File đã được chọn: {{ old('thumbnail') }}</p>
                                    @endif
                                    @error('thumbnail')
                                        <div class="form-text text-danger">{{ $message }}</div>
                                    @enderror 
                                </div>
                            </div>
                            
                            <!-- Public Date -->
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label text-xs-right"> Public Date: </label>
                                <div class="col-sm-10">
                                    <input type="datetime-local" name="public_date" class="form-control" style="max-width: 400px;" required value="{{ old('public_date') }}" min="<?php echo date('Y-m-d H:i'); ?>" id="public_date">
                                    <div class="form-text text-danger" id="error_public_date"></div>
                                    @error('public_date')
                                        <div class="form-text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label text-xs-right"> Content: </label>
                                <div class="col-sm-10">
                                    <textarea class="summernoteContent" name="content" id="content" required>
                                        {!! old('content') !!}
                                    </textarea>
                                    <div class="form-text text-danger" id="error_content"></div>
                                    @error('content')
                                        <div class="form-text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Banner: Show out slider_home -->
                            @if(Auth::user()->role == "admin") 
                            <div class="row form-group">
                                <label class="col-sm-2 form-control-label text-xs-right"> Is it displayed on the home banner? </label>
                                <div class="col-sm-10">
                                    <input type="checkbox" name="banner" value="1">
                                    @error('banner')
                                        <div class="form-text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            @endif
                            
                            <!-- Button -->
                            <div class="row form-group">
                                <div class="col-auto ml-md-auto">
                                    <button class="btn btn-primary btn-sm" type="submit">Create Post</button>
                                    <a class="btn btn-danger btn-sm ml-2" href="{{ url("/news") }}">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </form>
    @endif
@endsection