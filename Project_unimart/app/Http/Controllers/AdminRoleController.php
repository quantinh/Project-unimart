<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; //Phải khai báo khi tạo model theo kiểu Query builder
use Illuminate\Support\Facades\Auth;

class AdminRoleController extends Controller
{
    //Phương thức khởi tạo
    function __construct()
    {
        //Kiểm tra trước khi được vào trang này phải có quyền là 1(Administrator) và quyền thứ 3(SliderBanner)
        $this->middleware('CheckRole1');
        //Tạo ràng buộc kèm tham số next
        $this->middleware(function ($request, $next) {
            //Thêm key và value cho session
            session(['module_active' => 'role']);
            return $next($request);
        });
    }

    //Phương thức thêm quyền mới
    function add(Request $request)
    {
        //Lấy tất cả các quyền và sắp xếp theo thứ tự tăng dần
        $list_roles = DB::table('roles')
            ->orderby('id', 'asc')
            ->paginate('10');
        //Chuyển hướng và gửi dữ liệu qua view
        return view('admin.roles.list', compact('list_roles'));
    }

    //Phương thức thêm quyền mới validate dữ liệu trc khi lưu vào db
    function store(Request $request)
    {
        //Chuẩn hóa dữ liệu để gửi dữ liệu vào db
        $request->validate(
            //Quy tắt yêu cầu dữ liệu
            //Lưu ý: Nếu để các quy tắc cách nhau bởi dấy phẩy theo quy tắc đăng ký user của laravel thì quy tắc sau ko hoạt động, phải để theo quy tắc cách nhau |
            [
                'name_role' => 'required|string|unique:roles',
                'description' => 'required',
            ],
            //Cấu hình thông báo lỗi
            [
                'required' => ':attribute và không được để trống',
                'string' => ':attribute phải có dạng chuỗi',
                'unique' => ':attribute đã tồn tại trong hệ thống db',
            ],
            //Thay đổi field thành nội dung tiếng việt
            [
                'name_role' => 'Tên quyền',
                'description' => 'Mô tả nhóm quyền',
            ]
        );
        //Lấy tất cả dữ liệu được nhập ra
        //return $request->all();
        //Thì thêm tên quyền và user id vào db
        Role::create([
            'name_role' => $request->input('name_role'),
            'description' => $request->input('description'),
            'user_id' => Auth::id(),
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);
        return redirect('admin/roles/list')->with('status', "Đã thêm quyền mới thành công");
    }

    //Danh sách quyền hiện có của thành viên
    function list(Request $request)
    {
        //Lấy tất cả tên quyền ko trùng
        $list_roles = DB::table('roles')
            ->orderby('id', 'asc')
            ->paginate('10');
        //Trả lại cho view với những dữ liệu trên để hiển thị
        return view('admin.roles.list', compact('list_roles'));
    }

    //Phương thức cập nhập quyền cho thành viên
    function update(Request $request, $id)
    {
        //Chuẩn hóa dữ liệu để gửi dữ liệu vào db
        $input = $request->all();
        //Quy tắt yêu cầu dữ liệu
        //Lưu ý: Nếu để các quy tắc cách nhau bởi dấy phẩy theo quy tắc đăng ký user của laravel thì quy tắc sau ko hoạt động, phải để theo quy tắc cách nhau |
        $request->validate(
            [
                'name_role' => 'required|string',
                'description' => 'required|string',
            ],
            //Cấu hình thông báo lỗi
            [
                'required' => ':attribute và không được để trống',
                'string' => ':attribute phải có dạng chuỗi'
            ],
            //Thay đổi field thành nội dung tiếng việt
            [
                'name_role' => 'Tên quyền',
                'description' => 'Mô tả nhóm quyền',
            ]
        );
        // return $request->all();
        //Từ model roles cập nhập dữ liệu lưu vào database
        DB::table('roles')
        ->where('id', $id)
        ->update([
            'name_role' => $request->input('name_role'),
            'description' => $request->input('description'),
            'user_id' => Auth::id(),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);
        return redirect('admin/roles/list')->with('status', 'Cập nhật quyền cho thành viên thành công');
    }

    //Phương thức chỉnh sửa thông tin quyền thành viên
    function edit($id)
    {
        //Tìm kiếm quyền theo id
        $edit_role = Role::find($id);
        //Lấy tất cả các quyền và sắp xếp theo thứ tự tăng dần
        $list_roles = DB::table('roles')
        ->orderby('id', 'asc')
        ->paginate('10');
        //Trả dữ liệu được lấy ra từ table của db về trang chỉnh sửa trang view
        return view('admin.roles.edit', compact('edit_role', 'list_roles'));
    }

    //Phương thức xóa quyền thành viên
    function delete($id)
    {
        //Tìm trang theo id và xóa
        $role = DB::table('roles')
            ->where('id', $id)
            ->delete();
        //Chuyển hướng khi xóa xong kèm thông báo
        return redirect('admin/roles/list')->with('status', 'Xóa quyền cho thành viên thành công');
    }
}
