{{-- Phần layout kế thừ lại từ master trang chủ user --}}
@extends('layouts.master')

{{-- Định nghĩa nội dung chia nhỏ các thành phần ra rồi gộp lại bằng include --}}
@section('content')
    {{-- Top-content --}}
    <div class="container sm-gutter">
        <!-- Content-top  -->
        <div class="grid wide" style="padding-top: 35px; padding-bottom: 35px;">
            <div class="row sm-gutter app-content">
                <div class="col-7">
                    <h3 class="member-title">Tài khoản hệ thống</h3>
                    <p class="paragraph mb-0">Giúp bạn dễ dàng nhanh chóng nhận hàng hóa vận chuyển chỉ trong nháy
                        mắt.</p>
                    <p class="paragraph">Nhanh tay đăng kí thôi nào! để nhận ưu đãi từ cửa hàng chúng tôi.</p>
                    <img class="background" src="{{ asset('images/backgrounds/bg-reg.png') }}" alt="banner">
                </div>
                <div class="col-5">
                    <div class="container">
                        <div class="col-12">
                            <div class="form-block">
                                <div class="text-center mb-5">
                                    <h3 class="heading-title">Đăng ký tài khoản</h3>
                                </div>
                                {{-- Thông báo không có quyền truy cập --}}
                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                <form method="POST" action="{{ route('user.register') }}">
                                    @csrf
                                    <div class="form-group">
                                        <input type="text" name="fullname" class="form-control @error('fullname') is-invalid @enderror" placeholder="Họ và tên"
                                            id="fullname-reg">
                                    </div>
                                    @error('fullname')
                                        <span class="invalid-feedback" role="alert">
                                            <strong class="text-error">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="form-group">
                                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                                            placeholder="Tên đăng nhập" id="username">
                                    </div>
                                    @error('username')
                                        <span class="invalid-feedback" role="alert">
                                            <strong class="text-error">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="form-group">
                                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Mật khẩu"
                                            id="password">
                                    </div>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong class="text-error">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="form-group">
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email"
                                            id="email-reg">
                                    </div>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong class="text-error">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="form-group">
                                        <input type="radio" name="gender" checked="checked" value="Nam">
                                        <label for="gender" id="gender">Nam</label>
                                        <input type="radio" name="gender" value="Nữ">
                                        <label for="gender" id="gender">Nữ</label>
                                    </div>
                                    @error('gender')
                                        <span class="invalid-feedback" role="alert">
                                            <strong class="text-error">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <input type="submit" name="btn-reg" value="Đăng kí" class="btn btn-block btn-primary btn-submit" id="submit">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
