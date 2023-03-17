{{-- Phần layout kế thừ lại từ master trang chủ user  --}}
@extends('layouts.master')

{{-- Định nghĩa nội dung chia nhỏ các thành phần ra rồi gộp lại bằng include  --}}
@section('content')
    {{-- Top-content --}}
    <div class="app-container sm-gutter">
        <div class="grid wide">
            {{-- Body-content --}}
            <div class="row sm-gutter app-content" style="padding-top: 12px;">
                {{-- Sidebar-detail  --}}
                @include('posts.components.sidebar-detail')
                {{-- List-detail-post  --}}
                @include('posts.components.detail-post')
            </div>
        </div>
    </div>
@endsection


