<?php

namespace App\Http\Controllers;

use App\Menu;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Components\MenuRecusive;

class AdminMenuController extends Controller
{
    private $menuRecusive;
    //Phương thức khởi tạo
    function __construct(MenuRecusive $menuRecusive)
    {
        //Kiểm tra trước khi được vào trang này phải có quyền là 1(Administrator)
        $this->middleware('CheckRole5');
        //Tạo ràng buộc kèm tham số next
        $this->middleware(function ($request, $next) {
            //Thêm key và value cho session
            session(['module_active' => 'menu']);
            return $next($request);
        });

        $this->menuRecusive = $menuRecusive;
    }

    //Phương thức thêm menu mới
    function add(Request $request)
    {
        $optionSelect = $this->menuRecusive->menuRecusiveAdd();
        // dd($optionSelect);
        //Danh sách quyền cho selected
        // $list_menus = Menu::all();
        return view('admin.menus.add', compact('optionSelect'));
    }

    //Phương thức thêm menu mới validate trước khi lưu vào db
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
                'name_menu' => 'required|string|unique:menus',
                'parent_id' => 'required',
            ],
            //Cấu hình thông báo lỗi
            [
                'required' => ':attribute không được để trống',
                'string' => ':attribute phải có dạng chuỗi',
                'unique' => ':attribute đã tồn tại trong hệ thống database',
            ],
            //Thay đổi field thành nội dung tiếng việt
            [
                'name_menu' => 'Tên menu',
                'parent_id' => 'Menu cha',
            ]
        );
        //Lấy tất cả dữ liệu được nhập ra
        // return $request->all();

        //Tên slug bằng dữ liệu nhập tên danh mục chuyển thành chuỗi ko dấu ngăn cách bởi dấu chấm phẩy
        $slug_name = $request->input('name_menu');
        $slug = Str::slug($slug_name);
        $input['slug'] = $slug;

        //Từ model Menu tạo dữ liệu lưu vào database
        Menu::create([
            'name_menu' => $request->input('name_menu'),
            'slug' => $input['slug'],
            'parent_id' => $request->input('parent_id'),
            'status' =>  $request->input('status'),
            'user_id' => Auth::id(),
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);
        //Chuyển hướng theo url của route thêm dữ liệu xong thì chuyển hướng tới trang hiển thị danh sách
        return redirect('admin/menus/list')->with('status', 'Bạn đã thêm menu mới thành công');
    }

    //Phương thức hiển thị danh sách menu
    function list(Request $request)
    {
        //Lấy danh sách trang kể cả trong thùng rác join users lấy field tên người tạo
        $list_menus = Menu::withTrashed()
        ->join('users', 'users.id', '=', 'menus.user_id')
        ->select('menus.*', 'users.name')
        ->paginate(5);
        //Trả lại cho view với những dữ liệu trên để hiển thị
        return view('admin.menus.list', compact('list_menus'));
    }

    //Phương thức cập nhập thông tin menu
    function update(Request $request, $id)
    {
        //Chuẩn hóa dữ liệu để gửi dữ liệu vào db
        $input = $request->all();
        //Quy tắt yêu cầu dữ liệu
        $request->validate(
            //Quy tắt yêu cầu dữ liệu
            //Lưu ý: Nếu để các quy tắc cách nhau bởi dấy phẩy theo quy tắc đăng ký user của laravel thì quy tắc sau ko hoạt động, phải để theo quy tắc cách nhau |
            [
                'name_menu' => 'required|string|unique:menus',
                'parent_id' => 'required',
            ],
            //Cấu hình thông báo lỗi
            [
                'required' => ':attribute không được để trống',
                'string' => ':attribute phải có dạng chuỗi',
                'unique' => ':attribute đã tồn tại trong hệ thống database',
            ],
            //Thay đổi field thành nội dung tiếng việt
            [
                'name_menu' => 'Tên menu',
                'parent_id' => 'Menu cha',
            ]
        );

        //Lấy tất cả dữ liệu được nhập ra
        // return $request->all();

        //Tên slug bằng dữ liệu nhập tên danh mục chuyển thành chuỗi ko dấu ngăn cách bởi dấu chấm phẩy
        $slug_name = $request->input('name_menu');
        $slug = Str::slug($slug_name);
        $input['slug'] = $slug;

        //Từ model menu tạo dữ liệu lưu vào database
        Menu::where('id', $id)
            ->update([
                'name_menu' => $request->input('name_menu'),
                'slug' => $input['slug'],
                'parent_id' => $request->input('parent_id'),
                'user_id' => Auth::id(),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ]);
        //Chuyển hướng theo url của route thêm dữ liệu xong thì chuyển hướng tới trang hiển thị danh sách
        return redirect('admin/menus/list')->with('status', 'Bạn đã cập nhập lại menu thành công');
    }

    //Phương thức chỉnh sửa thông tin menu
    function edit($id)
    {
        //Danh sách quyền thành viên
        $menus = Menu::withTrashed()->find($id);
        // return $settings;
        //Lấy đệ quy tìm theo cha tương ứng
        $optionSelect = $this->menuRecusive->menuRecusiveEdit($menus->parent_id);
        //Chuyển dữ liệu từ db về qua view để thực hiện sửa thông tin
        return view('admin.menus.edit', compact('menus', 'optionSelect'));
    }

    //Phương thức thay đổi trạng thái menu
    function changeStatus($id)
    {
        //Lấy danh sách trang kể cả xóa tạm
        $menu = Menu::withTrashed()->where('id', $id)->get();
        foreach ($menu as $value) {
            //Nếu là trạng thái chờ duyệt thì khôi phục lại từ trash cập nhập lại giá trị
            if ($value->status != "Công khai") {
                Menu::onlyTrashed()->where('id', $id)->restore();
                Menu::where('id', $id)->update(['status' => 'Công khai']);
            } else {
                //Ngược lại thì xóa tạm thay đổi thành chờ duyệt
                Menu::where('id', $id)->update(['status' => 'Chờ duyệt']);
                Menu::withoutTrashed()->where('id', $id)->delete();
            }
            break;
        }
        //Chuyển hướng khi xóa xong kèm thông báo
        return redirect('admin/menus/list')->with('status', 'Thay đổi trạng thái menu thành công');
    }

    //Phương thức xóa vĩnh viễn menu
    function delete($id)
    {
        Menu::where('id', $id)->forceDelete();
        //Chuyển hướng khi xóa xong kèm thông báo
        return redirect('admin/menus/list')->with('status', 'Xóa menu thành công');
    }
}
