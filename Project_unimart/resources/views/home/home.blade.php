{{-- Phần layout kế thừ lại từ master trang chủ user  --}}
@extends('layouts.master')

{{-- Định nghĩa nội dung chia nhỏ các thành phần ra rồi gộp lại bằng include  --}}
@section('content')
    {{-- Top-content --}}
    <div class="app-container sm-gutter">
        <div class="grid wide">
            <!-- Content-top  -->
            <div class="row sm-gutter app-content">
                <!-- Category  -->
                @include('home.components.category')
                <!-- Slider  -->
                @include('home.components.slider')
                <!-- Banner  -->
                @include('home.components.banner')
            </div>
            {{-- Body-content --}}
            <div class="row sm-gutter app-content" style="padding-top: 12px;">
                {{-- Sidebar  --}}
                @include('components.sidebar')
                {{-- Product-futured  --}}
                @include('home.components.product-category')
            </div>
        </div>
    </div>
@endsection

<!-- Modal-Lấy nội dung chỉ được hiển thị ở home  -->
@include('components.modal')

