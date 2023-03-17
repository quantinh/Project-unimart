<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Font-awesome --}}
    <link rel="stylesheet" href="{{ asset('css/font-awesome/fontawesome-free-6.1.1-web/css/all.min.css') }}">
    {{-- Google-font --}}
    <link href="//fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700" rel="stylesheet">
    <link href="//fonts.googleapis.com/css?family=Josefin+Slab:100,300,400,600,700" rel="stylesheet">
    {{-- Lib-jquery --}}
    <script src="{{ asset('js/jquery-3.2.1.js') }}"></script>
    {{-- CSS-style --}}
    <link rel="stylesheet" href="{{ asset('css/login_admin.css') }}">
    {{-- Icon-load --}}
    <link rel=icon href="{{ asset('images/reloads/admin.jpg') }}" type="image/png" sizes="32x32" type="image/png">
    <title>Admin Login</title>
</head>

<body>
    <!-- From-login  -->
    <div class="box-layout-info">
        <div class="box-form">
            <h2>ADMIN</h2>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-sub">
                    <input type="email" name="email" placeholder="Email admin"
                        class="@error('email') is-invalid @enderror" value="{{ old('email') }}" required
                        autocomplete="email" autofocus id="emailad" />
                    <div class="box-icon">
                        <i class="fa-solid fa-user"></i>
                    </div>
                </div>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong class="text-error">{{ $message }}</strong>
                    </span>
                @enderror
                <div class="form-sub">
                    <input type="password" name="password" placeholder="Mật khẩu"
                        class="@error('password') is-invalid @enderror" name="password" required
                        autocomplete="current-password" id="passwordad" />
                    <div class="box-icon eye">
                        <i class="fas fa-eye-slash"></i>
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong class="text-error">{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <label class="box-sub">
                    <input type="checkbox" class="checkbox" id="remember"
                        {{ old('remember') ? 'checked' : '' }} />
                    <span for="remember">{{ __('Ghi nhớ') }}</span>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}">{{ __('Quên mật khẩu ?') }}</a>
                    @endif
                </label>
                <div class="submit-forms">
                    <input type="submit" value="Đăng nhập" id="submitad">
                </div>
            </form>
        </div>
    </div>
    {{-- Script --}}
    <script src="{{ asset('js/app.js') }}"></script>
</body>
