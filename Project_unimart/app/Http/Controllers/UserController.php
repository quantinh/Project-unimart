<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; //Phải khai báo khi tạo model theo kiểu Query builder
use Gloudemans\Shoppingcart\Facades\Cart;

class UserController extends Controller

{
    //Phương thức hiển thị form đăng kí
    function showFormRegister()
    {
        //Lấy ra tên menu 1
        $list_name_menu_one = Menu::where('name_menu', 'Trang chủ')->first();
        //Lấy ra tên menu 2
        $list_name_menu_two = Menu::where('name_menu', 'Sản phẩm')->first();
        //Lấy ra tên menu 3
        $list_name_menu_tree = Menu::where('name_menu', 'Blog')->first();
        //Danh sách trang menu header
        $list_menus = Page::all();
        //Chuyển hướng qua view
        return view('users.register', compact('list_name_menu_one', 'list_name_menu_two', 'list_name_menu_tree', 'list_menus'));
    }

    //Phương thức validate đăng kí
    function register(Request $request)
    {
        //Chuẩn hóa dữ liệu để gửi dữ liệu vào database
        $request->validate(
            //Quy tắt yêu cầu dữ liệu
            //Lưu ý: Nếu để các quy tắc cách nhau bởi dấy phẩy theo quy tắc đăng ký user của laravel thì quy tắc sau ko hoạt động, phải để theo quy tắc cách nhau |
            [
                'fullname' => 'required|string',
                'username' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:account_users',
                'password' => 'required|string|min:8',
                'gender' => 'required'
            ],
            //Cấu hình thông báo lỗi
            [
                'required' => ':attribute không được để trống',
                'string' => ':attribute phải chuỗi các là kí tự',
                'min' => ':attribute phải có độ dài ít nhất :min ký tự',
                'max' => ':attribute phải có độ dài tối :max ký tự',
                'unique' => ':attribute đã tồn tại trong hệ thống',
            ],
            //Thay đổi field thành nội dung tiếng việt
            [
                'username' => 'Tên người dùng',
                'fullname' => 'Họ và tên',
                'email' => 'Email',
                'password' => 'Mật khẩu',
                'gender' => 'Giới tính'
            ]
        );
        //Dữ liệu nhập lưu vào db
        $data = array();
        $data['username'] = $request->username;
        $data['fullname'] = $request->fullname;
        $data['email'] = $request->email;
        $data['gender'] = $request->gender;
        $data['password'] =  md5($request->password);
        $data['created_at'] = date('Y-m-d H:i:s', time());
        $data['updated_at'] = date('Y-m-d H:i:s', time());
        //Gom dữ liệu vào 1 mảng rồi thêm vào db lấy ra id riêng biệt
        $customer_id = DB::table('account_users')->insertGetId($data);
        //Lưu session hai trường cần hiển thị
        session()->put('id', $customer_id);
        session()->put('username', $request->username);
        //Chuyển hướng kèm thông báo
        return redirect()->route('form.login')->with('success', 'Đăng kí thành công');
    }

    //Phương thức hiển thị form đăng nhập
    function showFormLogin()
    {
        //Lấy ra tên menu 1
        $list_name_menu_one = Menu::where('name_menu', 'Trang chủ')->first();
        //Lấy ra tên menu 2
        $list_name_menu_two = Menu::where('name_menu', 'Sản phẩm')->first();
        //Lấy ra tên menu 3
        $list_name_menu_tree = Menu::where('name_menu', 'Blog')->first();
        //Danh sách trang menu header
        $list_menus = Page::all();
        //Chuyển hướng
        return view('users.login', compact('list_name_menu_one', 'list_name_menu_two', 'list_name_menu_tree', 'list_menus'));
    }

    //Phương thức validate đăng nhập
    function login(Request $request)
    {
        //Dữ liệu từ form gửi lên lấy ra
        $username = $request->username;
        $password = md5($request->password);
        //Đối chiếu mật khẩu của db với dữ liệu gửi lên nếu trùng thì lấy ra id
        $result = DB::table('account_users')
            ->where('username', $username)
            ->where('password', $password)
            ->first();
        //Kiểm tra nếu lấy ra được thì lưu id và username vào session
        if ($result) {
            //Lưu session hai trường cần hiển thị
            session()->put('id', $result->id);
            session()->put('username', $result->username);
            if(Cart::count() > 0) {
                return redirect()->route('cart.order');
            } else {
                return redirect('danh-sach-san-pham');
            }
        } else {
            return redirect()->route('form.login')->with('error', 'Email hoặc mật khẩu không chính xác');
        }
    }

    //Phương thức đăng xuất
    function logout()
    {
        //Lấy ra tên menu 1
        $list_name_menu_one = Menu::where('name_menu', 'Trang chủ')->first();
        //Lấy ra tên menu 2
        $list_name_menu_two = Menu::where('name_menu', 'Sản phẩm')->first();
        //Lấy ra tên menu 3
        $list_name_menu_tree = Menu::where('name_menu', 'Blog')->first();
        //Danh sách trang menu header
        $list_menus = Page::all();
        //Xóa session khi đăng xuất
        $id_session = Session()->forget('id');
        $username_session = session()->forget('username');
        //Chuyển hướng ra login
        return view('users.login', compact('list_name_menu_one', 'list_name_menu_two', 'list_name_menu_tree', 'list_menus'));
    }
}
