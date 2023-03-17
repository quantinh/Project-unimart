<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    //Để thế này thì khi logout sẽ chuyển về trạng ngoài cùng (trang chủ của hệ thống laravel 7.x)
    // use AuthenticatesUsers;
    use AuthenticatesUsers {
        //Cấu trúc 1
        logout as performLogout;
    }
    //Phần 24 bài 266
    public function logout(Request $request)
    {
        //Cấu trúc 2
        $this->performLogout($request);
        return redirect()->route('login'); //Chuyển hướng đến route login
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
