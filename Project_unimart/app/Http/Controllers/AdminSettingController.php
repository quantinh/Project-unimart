<?php

namespace App\Http\Controllers;

use App\User;
use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AdminSettingController extends Controller
{
    //Phương thức khởi tạo
    function __construct()
    {
        //Kiểm tra trước khi được vào trang này phải có quyền là 1(Administrator) và quyền thứ 3(SliderBanner)
        $this->middleware('CheckRole5');
        //Tạo ràng buộc kèm tham số next
        $this->middleware(function ($request, $next) {
            //Thêm key và value cho session
            session(['module_active' => 'setting']);
            return $next($request);
        });
    }

    //Phương thức thêm thiết lập mới
    function add(Request $request)
    {
        //Danh sách thiết lập
        $list_settings = Setting::all();
        return view('admin.settings.add', compact('list_settings'));
    }

    //Phương thức thêm setting mới validate trước khi lưu vào db
    function store(Request $request)
    {
        //Kiểm tra dữ liệu gửi lên đã nhấn button chưa ?
        if ($request->input('btn-add')) {
            // return $request->input();//xem tat ca
            // return $request->input('name');//xem tat ca
        }
        //Chuẩn hóa dữ liệu để gửi dữ liệu vào database
        $request->validate(
            //Quy tắt yêu cầu dữ liệu
            //Lưu ý: Nếu để các quy tắc cách nhau bởi dấy phẩy theo quy tắc đăng ký user của laravel thì quy tắc sau ko hoạt động, phải để theo quy tắc cách nhau |
            [
                'config_key' => 'required|unique:settings|max:255',
                'config_value' => 'required|unique:settings',
            ],
            //Cấu hình thông báo lỗi
            [
                'required' => ':attribute không được để trống',
                'string' => ':attribute phải có dạng chuỗi',
                'unique' => ':attribute đã tồn tại trong hệ thống database',
                'max' => ':attribute có độ dài tối đa :max ký tự',
            ],
            //Thay đổi field thành nội dung tiếng việt
            [
                'config_key' => 'Cấu hình từ khóa',
                'config_value' => 'Cấu hình giá trị',
            ]
        );
        //Lấy tất cả dữ liệu được nhập ra
        // return $request->all();

        //Từ model setting tạo dữ liệu lưu vào database
        Setting::create([
            'config_key' => $request->input('config_key'),
            'config_value' => $request->input('config_value'),
            'type' => $request->type,
            'status' =>  $request->input('status'),
            'user_id' => Auth::id(),
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);
        //Chuyển hướng theo url của route thêm dữ liệu xong thì chuyển hướng tới trang hiển thị danh sách
        return redirect('admin/settings/list')->with('status', 'Bạn đã thêm thiết lập mới thành công');
    }

    //Phương thức hiển thị danh sách thiết lập
    function list(Request $request)
    {
        //Lấy danh sách trang kể cả trong thùng rác join users lấy field tên người tạo
        $list_settings = Setting::withTrashed()
        ->join('users', 'users.id', '=', 'settings.user_id')
        ->select('settings.*', 'users.name')
        //Hiển thị 10 phần tử 1 trang
        ->paginate(5);
        //Trả lại cho view với những dữ liệu trên để hiển thị
        return view('admin.settings.list', compact('list_settings'));
    }

    //Phương thức cập nhập thiết lập
    function update(Request $request, $id)
    {
        //Chuẩn hóa dữ liệu để gửi dữ liệu vào db
        $input = $request->all();
        //Quy tắt yêu cầu dữ liệu
        $request->validate(
            [
                'config_key' => 'required|unique:settings|max:255',
                'config_value' => 'required|unique:settings',
            ],
            //Cấu hình thông báo lỗi
            [
                'required' => ':attribute không được để trống',
                'string' => ':attribute phải có dạng chuỗi',
                'unique' => ':attribute đã tồn tại trong hệ thống database',
                'max' => ':attribute có độ dài tối đa :max ký tự',
            ],
            //Thay đổi field thành nội dung tiếng việt
            [
                'config_key' => 'Cấu hình từ khóa',
                'config_value' => 'Cấu hình giá trị',
            ]
        );

        //Lấy tất cả dữ liệu được nhập ra
        // return $request->all();

        //Từ model setting tạo dữ liệu lưu vào database
        Setting::where('id', $id)
            ->update([
                'config_key' => $request->input('config_key'),
                'config_value' => $request->input('config_value'),
                'user_id' => Auth::id(),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ]);
        //Chuyển hướng theo url của route thêm dữ liệu xong thì chuyển hướng tới trang hiển thị danh sách
        return redirect('admin/settings/list')->with('status', 'Bạn đã cập nhập lại thiết lập thành công');
    }

    //Phương thức chỉnh sửa thông tin thiết lập
    function edit($id)
    {
        //Danh sách quyền thành viên
        $settings = Setting::withTrashed()->find($id);
        // return $settings;
        //Chuyển dữ liệu từ db về qua view để thực hiện sửa thông tin
        return view('admin.settings.edit', compact('settings'));
    }

    // Phương thức thay đổi thiết lập
    function changeStatus($id)
    {
        //Lấy danh sách trang kể cả xóa tạm
        $setting = Setting::withTrashed()->where('id', $id)->get();
        foreach ($setting as $value) {
            //Nếu là trạng thái chờ duyệt thì khôi phục lại từ trash cập nhập lại giá trị
            if ($value->status != "Công khai") {
                Setting::onlyTrashed()->where('id', $id)->restore();
                Setting::where('id', $id)->update(['status' => 'Công khai']);
            } else {
                //Ngược lại thì xóa tạm thay đổi thành chờ duyệt
                Setting::where('id', $id)->update(['status' => 'Chờ duyệt']);
                Setting::withoutTrashed()->where('id', $id)->delete();
            }
            break;
        }
        //Chuyển hướng khi xóa xong kèm thông báo
        return redirect('admin/settings/list')->with('status', 'Thay đổi trạng thái thiết lập thành công');
    }

    //Phương thức xóa vĩnh viễn thiết lập
    function delete($id)
    {
        Setting::where('id', $id)->forceDelete();
        //Chuyển hướng khi xóa xong kèm thông báo
        return redirect('admin/settings/list')->with('status', 'Xóa thiết lập thành công');
    }
}
