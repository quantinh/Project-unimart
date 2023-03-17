{{-- Phần layout kế thừ lại từ master trang chủ user  --}}
@extends('layouts.master')

{{-- Định nghĩa nội dung chia nhỏ các thành phần ra rồi gộp lại bằng include  --}}
@section('content')
    {{-- Top-content --}}
    <div class="container sm-gutter">
        <div class="grid wide" style="padding-top: 35px; padding-bottom: 35px;">
            {{-- Body-content --}}
            <div class="row sm-gutter app-content">
                <div class="col-7">
                    <img class="background" src="{{ asset('images/backgrounds/background-user-login.png') }}" alt="">
                </div>
                <div class="col-5">
                    <div class="container">
                        <div class="col-12">
                            <div class="form-block">
                                <div class="text-center mb-5">
                                    <h3 class="heading-title">Đăng nhập</h3>
                                </div>
                                {{-- Thông báo không có quyền truy cập --}}
                                @if (session('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                @endif
                                <form method="POST" action="{{ route('user.login') }}">
                                    @csrf
                                    <div class="form-group">
                                        <input type="text" name="username" class="form-control" placeholder="Tên đăng nhập" id="username">
                                    </div>
                                    <div class="form-group mb-3 box-pass">
                                        <input type="password" name="password" class="form-control" placeholder="Mật khẩu" id="password">
                                        <div class="eye" id="box-pass">
                                            <i class="far fa-eye-slash pb-2 px-2"></i>
                                        </div>
                                    </div>
                                    <div class="d-sm-flex mt-4 mb-4">
                                        <span class="mr-auto d-flex">
                                            <input type="checkbox"><p class="forgot-pass">Ghi nhớ đăng nhập</p>
                                        </span>
                                    </div>
                                    <input type="submit" name="btn-login" value="Đăng nhập" class="btn btn-block btn-primary btn-submit">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


