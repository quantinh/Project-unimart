<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use Illuminate\Support\Facades\DB; //Phải khai báo khi tạo model theo kiểu Query builder
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class AdminUserController extends Controller
{
    //Phương thức khởi tạo
    function __construct()
    {
        //Kiểm tra trước khi được vào trang này phải có quyền là 1(Administrator) và quyền thứ 3(SliderBanner)
        $this->middleware('CheckRole1');
        //Tạo ràng buộc kèm tham số next
        $this->middleware(function ($request, $next) {
            //Thêm key và value cho session
            session(['module_active' => 'user']);
            return $next($request);
        });
    }

    //Phương thức thêm thành viên mới
    function add(Request $request)
    {
        //Danh sách quyền cho selected
        $list_roles = Role::all();
        return view('admin.users.add', compact('list_roles'));
    }

    //Phương thức thêm thành viên mới validate trước khi lưu vào db
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
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'password_confirmation' => 'required|same:password',
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'role_id' => 'required'
            ],
            //Cấu hình thông báo lỗi
            [
                'required' => ':attribute không được để trống',
                'min' => ':attribute phải có độ dài ít nhất :min ký tự',
                'max' => ':attribute phải có độ dài tối :max ký tự',
                'unique' => ':attribute đã tồn tại trong hệ thống',
                'same' => ':attribute phải giống mật khẩu vừa nhập',
                'file.required' => ':attribute chưa được chọn',
                'image' => ':attribute phải có dạng file ảnh',
                'mimes' => ':attribute ảnh có đuôi dạng jpeg,png,jpg,gif,svg',
                'file.max' => ':attribute ảnh có dung lượng dưới 2048kb',
            ],
            //Thay đổi field thành nội dung tiếng việt
            [
                'name' => 'Tên người dùng',
                'email' => 'Email',
                'password' => 'Mật khẩu',
                'password_confirmation' => 'Xác nhận mật khẩu',
                'file' => 'File',
                'role_id' => 'Quyền'
            ]
        );
        //Lấy tất cả dữ liệu được nhập ra
        //return $request->all();
        //Nếu có file thì xuất các thông của file
        if ($request->hasFile('file')) {
            $file = $request->file;
            // echo $file;
            //Lấy tên file
            $filename = $file->getClientOriginalName();
            if (!file_exists('public/images/avatars/' . $filename)) {
                //Chuyển file lên server (trong folder public/uploads)
                $path = $file->move('public/images/avatars', $file->getClientOriginalName());
                //Đường dẫn của file lưu vào database
                $thumbnail = 'images/avatars/' . $filename;
            } else {
                $newfilename = time() . '-' . $filename;
                $path = $file->move('public/images/avatars', $newfilename); //Chuyển file lên server(trong folder public/uploads)
                $thumbnail = 'images/avatars/' . $newfilename; //Đường dẫn của file lưu vào database
            }
            //Tạo trường thumbnail và thêm đường dẫn file vào
            $input['avatar'] = $thumbnail;
        }
        //Từ model User tạo dữ liệu lưu vào database
        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' =>  Hash::make($request->input('password')),
            'role_id' => $request->input('role_id'),  //Input=name=value=>view
            'avatar' => $input['avatar'],
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);
        //Chuyển hướng theo url của route thêm dữ liệu xong thì chuyển hướng tới trang hiển thị danh sách
        return redirect('admin/users/list')->with('status', 'Bạn đã thêm thành viên mới thành công');
    }

    //Phương thức hiển thị danh sách thành viên admin
    function list(Request $request)
    {
        //Lấy giá trị status ở url
        $status = $request->input('status');
        //Phần 24 bài 277: Xóa vĩnh viễn user
        $list_act = [
            'delete' => 'Xóa tạm thời',
        ];
        //Nếu url status có trạng thái là thùng rác thì xuất ra danh sách được khôi phục và xóa vĩnh viễn
        if ($status == 'trash') {
            $list_act = [
                'restore' => 'Khôi phục',
                'forceDelete' => 'Xóa vĩnh viễn'
            ];
            //Thì lấy những bảng ghi đã xóa tạm thời và phân trang hiển thị và ngược lại thì hiển thị theo những trạng thái đã kích hoạt
            $users = User::onlyTrashed()
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->select('users.*', 'users.name', 'roles.name_role')
            ->paginate(10);
            //Ngược lại nếu ko có trạng thái thì tìm kiếm theo keyword nếu keyword null thì phân trang như cũ
        } else {
            $keyword = "";
            if ($request->input('keyword')) {
                $keyword = $request->input('keyword');
            }
            //Lấy danh sách thành viên kèm quyền
            $users = User::where('name', 'LIKE', "%{$keyword}%")
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->select('users.*', 'users.name', 'roles.name_role')
            ->paginate(10);
            //Xuất dữ liệu đã lấy được
            // dd($users->total());
        }
        //Phần 24 bài 268
        //Lấy tổng số bảng ghi đã có kể cả thùng đã xóa tạm thời ở database
        $count_user_active = User::count();

        //Lấy tổng xóa bảng ghi đã bị xóa tạm thời ở database
        $count_user_trash = User::onlyTrashed()->count();

        //Tổng số dữ liệu của hai phần đã lấy ở trên
        $count = [$count_user_active, $count_user_trash];

        //Trả lại cho view với những dữ liệu trên để hiển thị
        return view('admin.users.list', compact('users', 'count', 'list_act'));
    }

    //Phương thức cập nhập thông tin
    function update(Request $request, $id)
    {
        //Chuẩn hóa dữ liệu để gửi dữ liệu vào db
        $input = $request->all();
        //Quy tắt yêu cầu dữ liệu
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'password' => 'required|string|min:8',
                'password_confirmation' => 'required|same:password',
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'role_id' => 'required'
            ],
            //Cấu hình thông báo lỗi
            [
                'required' => ':attribute không được để trống',
                'min' => ':attribute phải có độ dài ít nhất :min ký tự',
                'max' => ':attribute phải có độ dài tối :max ký tự',
                'unique' => ':attribute đã tồn tại trong hệ thống',
                'same' => ':attribute phải giống mật khẩu vừa nhập',
                'file.required' => ':attribute chưa được chọn',
                'image' => ':attribute phải có dạng file ảnh',
                'mimes' => ':attribute ảnh có đuôi dạng jpeg,png,jpg,gif,svg',
                'file.max' => ':attribute ảnh có dung lượng dưới 2048kb',
            ],
            //Thay đổi field thành nội dung tiếng việt
            [
                'name' => 'Tên người dùng',
                'password' => 'Mật khẩu',
                'password_confirmation' => 'Xác nhận mật khẩu',
                'file' => 'File',
                'role_id' => 'Quyền'
            ]
        );
        //Lấy tất cả dữ liệu được nhập ra
        //return $request->all();
        if ($request->hasFile('file')) {
            $file = $request->file;
            // echo $file;
            //Lấy tên file
            $filename = $file->getClientOriginalName();
            if (!file_exists('public/images/avatars/' . $filename)) {
                //Chuyển file lên server (trong folder public/uploads)
                $path = $file->move('public/images/avatars', $file->getClientOriginalName());
                //Đường dẫn của file lưu vào database
                $thumbnail = 'images/avatars/' . $filename;
            } else {
                $newfilename = time() . '-' . $filename;
                $path = $file->move('public/images/avatars', $newfilename); //Chuyển file lên server(trong folder public/uploads)
                $thumbnail = 'images/avatars/' . $newfilename; //Đường dẫn của file lưu vào database
            }
            //Tạo trường thumbnail và thêm đường dẫn file vào
            $input['avatar'] = $thumbnail;
        }
        //Xóa file ảnh
        $user = DB::table('users')->find($id);
        if (!empty($user)) {
            @unlink($user->avatar);
        }
        //Cập nhập thông tin user
        //Từ model User tạo dữ liệu lưu vào database
        User::where('id', $id)
            ->update([
                'name' => $request->input('name'),
                'password' =>  Hash::make($request->input('password')),
                'role_id' => $request->input('role_id'),
                'avatar' => $input['avatar'],
                'updated_at' => date('Y-m-d H:i:s', time())
            ]);
        //Chuyển hướng theo url của route thêm dữ liệu xong thì chuyển hướng tới trang hiển thị danh sách
        return redirect('admin/users/list')->with('status', 'Bạn đã cập nhập thông tin thành công');
    }

    //Phương thức chỉnh sửa thông tin thành viên
    function edit($id)
    {
        //Ngược lại nếu có quyền và login xác thực thì cho phép tìm kiếm table db theo id để thực thi chỉnh sửa thông tin cá nhân cho thành viên khác
        $users = User::find($id);
        //Danh sách quyền thành viên
        $list_roles = Role::all();
        //Chuyển dữ liệu từ db về qua view để thực hiện sửa thông tin
        return view('admin.users.edit', compact('users', 'list_roles'));
    }

    //Phương thức khôi phục thành viên
    function restore($id)
    {
        //Lấy theo id bài viết trong thùng rác khôi phục lại
        User::onlyTrashed()->where('id', $id)->restore();
        //Chuyển hướng khi xóa xong kèm thông báo
        return redirect('admin/users/list')->with('status', 'Kích hoạt lại thành viên cho thành công');
    }

    //Phương thức xóa tạm thời thành viên
    function delete($id)
    {
        //Xử lí quyền trước khi cho các thành viên admin thực hiện thao tác xóa
        $delete_user = User::find($id);
        //Duyệt qua các thành viên theo id để kiểm tra quyền field
        foreach ($delete_user->roles as $role) {
            //Theo điều kiện nếu có giá trị là admin chính thì chuyển hướng và ko cho xóa
            if ($role->name_role == 'Administrator') {
                return redirect('admin/users/list')->with('status', 'Bạn không được phép xóa thành viên này vì quyền của bạn đã bị giới hạn');
            }
        }
        //Nếu như id đã login khác id trong bảng thì được quyền xóa thông tin bảng ghi
        if (Auth::id() != $id) {
            $user = User::find($id);
            //Xóa tạm thành viên
            $user->delete();
            return redirect('admin/users/list')->with('status', 'Đã xóa tạm thời thành viên thành công');
        } else {
            return redirect('admin/users/list')->width('status', 'Bạn không thể tự xóa mình ra khỏi hệ thống');
        }
    }

    //Phương thức xóa sản phẩm vĩnh viễn
    function permanentlyDelete($id)
    {
        //Nếu như id đã login khác id trong bảng thì được quyền xóa thông tin bảng ghi
        User::onlyTrashed()->where('id', $id)
        ->forceDelete();
        //Chuyển hướng khi xóa xong kèm thông báo
        return redirect('admin/users/list')->with('status', 'Đã xóa vĩnh viễn thành viên thành công');
    }

    //Phương thức thực hiện tác vụ check
    function action(Request $request)
    {
        //Lấy ra danh sách phần tử đã chọn
        $list_check = $request->input('list_check');
        //Kiểm tra xem mảng $list_check['?'=> '?'] có phần tử nào ko ?
        if (isset($list_check)) {
            //Kiểm tra có trống dữ liệu trong mảng $list_check hay ko?
            if (!empty($list_check)) {
                //Lấy danh sách đã chọn tác vụ ra
                $act = $request->input('act');
                //Nếu tác vụ này là xóa thì kiểm tra điều kiện tiếp theo mới đk xóa
                if ($act == 'delete') {
                    //Trường hợp cố tình xóa quản trị viên chính xét xem nếu nếu trong danh sách có check từ 1 lần thì kiểm tra check đó
                    if (count($list_check) == 1) {
                        //Nếu admin này đã login và xác thực thì admin đó sẽ có key = 0; (là admin đầu tiên) và thực hiện chuyển hướng thông báo ko cho thực thi chính mình
                        if (Auth::id() == $list_check[0]) {
                            return redirect('admin/users/list')->with('status', 'Bạn không thể tự xóa mình ra khỏi hệ thống');
                            //Ngược lại nếu chưa login cũng như chưa xác thực thì duyệt tìm kiếm danh sách check thứ tự key = 0 kiểm tra với các field quyền
                        } else {
                            foreach (User::find($list_check[0])->roles as $role) {
                                //Nếu quyền là admin chính thì chuyển hướng và ko cho tự xóa bản thân admin chính
                                if ($role->name_role == 'Administrator') {
                                    return redirect('admin/users/list')->with('status', 'Bạn không thể xóa quản trị viên của hệ thống');
                                }
                            }
                        }
                    }
                    //Duyệt qua danh sách check với các id
                    foreach ($list_check as $k => $id) {
                        //Kiểm tra nếu id đó đã xác thực login và bằng chính id của mình thì loại bỏ khỏi danh sách check thực thi
                        if (Auth::id() == $id) {
                            unset($list_check[$k]); //Loại bo user dang login khi nguoi dung lo chon vao user dang login
                        }
                    }
                    //Duyệt qua danh sách check với các id
                    foreach ($list_check as $k => $id) {
                        //Tìm kiếm các thành viên theo id trên table users
                        $delete_user = User::find($id);
                        //Duyệt qua các thành viên với các quyền
                        foreach ($delete_user->roles as $role) {
                            //Nếu quyền là admin chính thì xóa khỏi danh sách check thực thi
                            if ($role->name_role == 'Administrator') {
                                unset($list_check[$k]);
                            }
                        }
                    }
                    //Từ model User xóa danh sách và chuyển hướng
                    User::destroy($list_check);
                    return redirect('admin/users/list')->with('status', 'Bạn đã xóa tạm thời thành viên thành công');
                }
                //Nếu tác vụ này là khôi phục thì cho phép khôi phục
                if ($act == 'restore') {
                    User::withTrashed()
                        //Điều kiện id này có trong mảng danh sách đã check cho khôi phục và chuyền hướng ra thông báo thành công
                        ->whereIn('id', $list_check)
                        ->restore();
                    return redirect('admin/users/list')->with('status', 'Bạn đã khôi phục thành viên thành công');
                }
                //Phần 24 bài 227: xóa vĩnh viễn nếu tác vụ này là xóa vĩnh viễn thì cho phép xóa vĩnh viễn
                if ($act == 'forceDelete') {
                    User::withTrashed()
                        //Điều kiện id có trong mảng $list_check
                        ->whereIn('id', $list_check)
                        ->forceDelete();
                    return redirect('admin/users/list')->with('status', 'Bạn đã xóa vĩnh viễn thành viên thành công');
                }
            }
            //Nếu trong mảng trống(không có tác vụ) thì chuyển hướng
            return redirect('admin/users/list')->with('status', 'Bạn không thể thao tác trên tài khoản của bạn');
        } else {
            //Nếu trong mảng trống(không có tác vụ) thì chuyển hướng
            return redirect('admin/users/list')->with('status', 'Bạn cần chọn phần tử cần thực thi');
        }
    }
}
