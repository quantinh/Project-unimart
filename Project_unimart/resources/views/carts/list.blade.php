{{-- Phần layout kế thừ lại từ master trang chủ user  --}}
@extends('layouts.master')

{{-- Định nghĩa nội dung chia nhỏ các thành phần ra rồi gộp lại bằng include  --}}
@section('content')
    {{-- Top-content --}}
    <div class="app-container sm-gutter">
        <div class="grid wide">
            <div class="row sm-gutter app-content">
                {{-- Content-cart  --}}
                @include('carts.components.list-cart')
            </div>
        </div>
    </div>
@endsection


