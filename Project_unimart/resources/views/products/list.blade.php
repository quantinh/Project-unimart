{{-- Phần layout kế thừ lại từ master trang chủ user  --}}
@extends('layouts.master')

{{-- Định nghĩa nội dung chia nhỏ các thành phần ra rồi gộp lại bằng include  --}}
@section('content')
    {{-- Top-content --}}
    <div class="app-container sm-gutter">
        <div class="grid wide">
            <div class="row" style="margin-left: 5px">
            <!-- Nav-home  -->
                <div class="section-product">
                    <ul class="list-section">
                        <i class="ml-0 item-section-icon fa-solid fa-house-chimney"></i>
                        <li class="item-section">
                            <a class="item-section-link" href="{{ url('/') }}" title="">Trang chủ</a>
                        </li>
                        <i class="item-section-icon fa-solid fa-angle-right"></i>
                        <li class="item-section">
                            <a class="item-section-link" href="{{ url('product') }}" title="">Sản phẩm</a>
                        </li>
                        <i class="item-section-icon fa-solid fa-angle-right"></i>
                        <li class="item-section">
                            <a class="item-section-link" href="{{ url('product') }}"
                                title="">Danh sách sản phẩm</a>
                        </li>
                    </ul>
                </div>
            </div>
            {{-- Body-content --}}
            <div class="row sm-gutter app-content">
                {{-- Sidebar-product  --}}
                @include('products.components.sidebar-product')
                {{-- List-product  --}}
                @include('products.components.list-product')
            </div>
        </div>
    </div>
@endsection


