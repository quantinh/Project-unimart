<?php

namespace App\Http\Middleware;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;


class CheckRole2
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next)
    {
        //Kiểm tra id (người dùng) đó đã xác thực chưa ?

        //Nếu ko đúng quyền 1(admin) hoặc 2(productorder) hoặc 6(tester) chuyển hướng về trang khác và trả về thông báo
        if(Auth::user()->role_id == 2 || Auth::user()->role_id == 1 || Auth::user()->role_id == 6) {
            return $next($request);
        } else {
            //Nếu ko đúng quyền 1 chuyển hướng về trang khác và trả về thông báo
            return redirect('admin')->with('status', 'Bạn không có quyền truy cập tính năng này');
        }
        // return response()->json('Bạn không có quyền truy cập tính năng này');
    }
}
