{{-- Phần layout kế thừ lại từ master trang chủ user  --}}
@extends('layouts.master')

{{-- Định nghĩa nội dung chia nhỏ các thành phần ra rồi gộp lại bằng include  --}}
@section('content')
    {{-- Top-content --}}
    <div class="app-container sm-gutter">
        <div class="grid wide">
            {{-- Body-content --}}
            <div class="row sm-gutter app-content" style="padding-top: 12px;">
                {{-- Sidebar-post  --}}
                @include('posts.components.sidebar-post')
                {{-- List-post  --}}
                @include('posts.components.list-post')
            </div>
        </div>
    </div>
@endsection


