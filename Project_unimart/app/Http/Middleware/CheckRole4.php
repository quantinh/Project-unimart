<?php

namespace App\Http\Middleware;
use App\User;
use Illuminate\Support\Facades\Auth;

use Closure;

class CheckRole4
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
        //Kiểm tra xem id (người dùng) đó đã xác thực chưa ?

        //Nếu ko đúng quyền 1(admin) hoặc 4(PagePostCat) hoặc quyền 6(Test) chuyển hướng về trang khác và trả về thông báo
        if(Auth::user()->role_id == 4 || Auth::user()->role_id == 6 ||  Auth::user()->role_id == 1) {
            return $next($request);
        } else {
            //Nếu ko đúng quyền 1 chuyển hướng về trang khác và trả về thông báo
            return redirect('admin')->with('status', 'Bạn không có quyền truy cập tính năng này');
        }
        // return response()->json('Bạn không có quyền truy cập tính năng này');
    }
}
