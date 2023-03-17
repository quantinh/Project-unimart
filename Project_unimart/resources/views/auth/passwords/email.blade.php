<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Font-awesome --}}
    <link rel="stylesheet" href="{{ asset('css/font-awesome/fontawesome-free-6.1.1-web/css/all.min.css')}}">
    {{-- google-font --}}
    <link href="//fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700" rel="stylesheet">
    <link href="//fonts.googleapis.com/css?family=Josefin+Slab:100,300,400,600,700" rel="stylesheet">
    {{-- Lib-jquery --}}
    <script src="{{ asset('js/jquery-3.2.1.js') }}"></script>
    {{-- CSS-style --}}
    <link rel="stylesheet" href="{{ asset('css/login_admin.css') }}">
    <link rel=icon href="{{ asset('images/reloads/admin.jpg') }}" type="image/png"sizes="32x32" type="image/png">
    <title>Reset password</title>
</head>

<body>
    <div class="box-layout-info" style="margin-top: 130px;">
        <div class="box-form">
            <h2>Nhập Email Admin</h2>
            @if (session('status'))
                <div class="alert alert-success text-error" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="form-sub">
                    <input type="email" placeholder="Email admin" id="emailad"
                        class="form-control @error('email') is-invalid @enderror" name="email"
                        value="{{ old('email') }}" required autocomplete="email" autofocus />
                    <div class="box-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong class="text-error">{{ $message }}</strong>
                    </span>
                @enderror
                <div class="submit-forms">
                    <input type="submit" value="Gửi yêu cầu đặt lại mật khẩu" id="submitad">
                </div>
                <div class="box-inf">
                    <span><a href="{{ route('login') }}">Đăng nhập |</a></span>
                    <span><a href="https://mail.google.com/mail/u/0/#inbox">Email</a></span>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
