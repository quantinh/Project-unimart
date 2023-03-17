<?php

namespace App\Http\Controllers;

use App\Page;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; //Phải khai báo khi tạo model theo kiểu Query builder
use Illuminate\Support\Facades\Auth;

class AdminPageController extends Controller
{
    //Phương thức khởi tạo
    function __construct()
    {
        //Kiểm tra trước khi được vào trang này phải có quyền là 1(Administrator) và quyền thứ 4(PagePostCat)
        $this->middleware('CheckRole4');
        //Tạo ràng buộc kèm tham số next
        $this->middleware(function ($request, $next) {
            // thêm key và value cho session
            session(['module_active' => 'page']);
            return $next($request);
        });
    }

    //Phương thức thêm trang mới
    function add(Request $request)
    {
        return view('admin.pages.add');
    }

    //Phương thức thêm trang mới validate trước khi lưu vào db
    function store(Request $request)
    {
        //Chuẩn hóa dữ liệu để gửi dữ liệu vào db
        $request->validate(
            //Quy tắt yêu cầu dữ liệu
            //Lưu ý: Nếu để các quy tắc cách nhau bởi dấy phẩy theo quy tắc đăng ký user của laravel thì quy tắc sau ko hoạt động, phải để theo quy tắc cách nhau |
            [
                'title' => 'required',
                'content' => 'required',
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
                'description' => 'required'
            ],
            //Cấu hình thông báo lỗi
            [
                'required' => ':attribute không được để trống',
                'image' => ':attribute ảnh có dạng file ảnh',
                'mimes' => ':attribute ảnh có đuôi dạng jpeg,png,jpg,gif,svg',
            ],
            //Thay đổi field thành nội dung tiếng việt
            [
                'title' => 'Tiêu đề bài viết của trang',
                'file' => 'Ảnh trang',
                'content' => 'Nội dung bài viết của trang',
                'description' => 'Mô tả bài viết của trang',
            ]
        );

        //Nếu có file thì xuất các thông của file
        if ($request->hasFile('file')) {
            $file = $request->file;
            $filename = $file->getClientOriginalName();
            if (!file_exists('public/images/pages/' . $filename)) {
                //Chuyển file lên server (trong folder public/uploads)
                $path = $file->move('public/images/pages', $file->getClientOriginalName());
                //Đường dẫn của file lưu vào database
                $thumbnail = 'images/pages/' . $filename;
            } else {
                $newfilename = time() . '-' . $filename;
                $path = $file->move('public/images/pages', $newfilename); //Chuyển file lên server(trong folder public/uploads)
                $thumbnail = 'images/pages/' . $newfilename; //Đường dẫn của file lưu vào database
            }
            //Tạo trường thumbnail và thêm đường dẫn file vào
            $input['thumbnail'] = $thumbnail;
        }
        //Tên slug bằng dữ liệu nhập tên danh mục chuyển thành chuỗi ko dấu ngăn cách bởi dấu chấm phẩy
        $slug_name = $request->input('title');
        $slug = Str::slug($slug_name);
        $input['slug'] = $slug;

        //Lấy tất cả dữ liệu được nhập ra
        // return $request->all();

        //Từ model pages tạo dữ liệu lưu vào database
        $page = Page::create([
            'title' => $request->input('title'),
            'slug' => $input['slug'],
            'thumbnail' => $input['thumbnail'],
            'content' => $request->input('content'),
            'description' => $request->input('description'),
            'status' => $request->input('status'),
            'user_id' => Auth::id()
        ]);

        //Nếu dữ liệu gửi lên có trạng thái chờ duyệt thì tìm kiếm trang theo id(trang đã thêm) đó xóa tạm để (thành trạng thái chờ duyệt khi thay đổi thì khôi phục lại và update thành công)
        if ($request->input('status') == "Chờ duyệt") {
            Page::find($page->id)->delete();
        }

        //Ngược lại thì thông báo và chuyển hướng
        return redirect('admin/pages/list')->with('status', 'Bạn đã thêm trang mới thành công');
    }

    //Phương thức hiển thị danh sách trang
    function list(Request $request)
    {
        //Lấy danh sách trang kể cả trong thùng rác join users lấy field tên người tạo
        $list_pages = Page::withTrashed()
        ->join('users', 'users.id', '=', 'pages.user_id')
        ->select('pages.*', 'users.name')
        ->paginate(10);
        //Trả lại cho view với những dữ liệu trên để hiển thị
        return view('admin.pages.list', compact('list_pages'));
    }

    //Phương thức cập nhập trạng
    function update(Request $request, $id)
    {
        //Chuẩn hóa dữ liệu để gửi dữ liệu vào db
        $input = $request->all();
        //Quy tắt yêu cầu dữ liệu
        //Lưu ý: Nếu để các quy tắc cách nhau bởi dấy phẩy theo quy tắc đăng ký user của laravel thì quy tắc sau ko hoạt động, phải để theo quy tắc cách nhau |
        $request->validate(
            [
                'title' => 'required',
                'content' => 'required',
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
                'description' => 'required'
            ],
            //Cấu hình thông báo lỗi
            [
                'required' => ':attribute không được để trống',
                'image' => ':attribute ảnh có dạng file ảnh',
                'mimes' => ':attribute ảnh có đuôi dạng jpeg,png,jpg,gif,svg',
            ],
            //Thay đổi field thành nội dung tiếng việt
            [
                'title' => 'Tiêu đề bài viết của trang',
                'file' => 'Ảnh trang',
                'content' => 'Nội dung bài viết của trang',
                'description' => 'Mô tả bài viết của trang',
            ]
        );
        //Nếu có file thì xuất các thông của file
        if ($request->hasFile('file')) {
            $file = $request->file;
            $filename = $file->getClientOriginalName();
            if (!file_exists('public/images/pages/' . $filename)) {
                //Chuyển file lên server (trong folder public/uploads)
                $path = $file->move('public/images/pages', $file->getClientOriginalName());
                //Đường dẫn của file lưu vào database
                $thumbnail = 'images/pages/' . $filename;
            } else {
                $newfilename = time() . '-' . $filename;
                $path = $file->move('public/images/pages', $newfilename); //Chuyển file lên server(trong folder public/uploads)
                $thumbnail = 'images/pages/' . $newfilename; //Đường dẫn của file lưu vào database
            }
            //Tạo trường thumbnail và thêm đường dẫn file vào
            $input['thumbnail'] = $thumbnail;
        }

        //Tên slug bằng dữ liệu nhập tên danh mục chuyển thành chuỗi ko dấu ngăn cách bởi dấu chấm phẩy
        $slug_name = $request->input('title');
        $slug = Str::slug($slug_name);
        $input['slug'] = $slug;

        //Lấy tất cả dữ liệu được nhập ra
        // return $request->all();

        //Từ model pages cập nhập dữ liệu lưu vào database
        $page = Page::where('id', $id)
            ->update([
                'title' => $request->input('title'),
                'slug' => $input['slug'],
                'thumbnail' => $input['thumbnail'],
                'content' => $request->input('content'),
                'description' => $request->input('description'),
                'status' => $request->input('status'),
                'user_id' => Auth::id()
        ]);

        //Nếu dữ liệu gửi lên có trạng thái chờ duyệt thì tìm kiếm trang theo id(trang đã thêm) đó xóa tạm để (thành trạng thái chờ duyệt khi thay đổi thì khôi phục lại và update thành công)
        if ($request->input('status') == "Chờ duyệt") {
            Page::find($id)->delete();
        }
        //Ngược lại thì thông báo và chuyển hướng
        return redirect('admin/pages/list')->with('status', 'Cập nhật bài viết cho trang thành công');
    }

    //Phương thức cập nhập thông tin trang
    function edit($id)
    {
        //Tìm những trang kể cả trong thùng rác dể chỉnh sửa
        $page = Page::withTrashed()->find($id);
        //Trả dữ liệu được lấy ra từ table của db về trang chỉnh sửa trang view
        return view('admin.pages.edit', compact('page'));
    }

    //Phương thức thay đổi trạng thái trang
    function changeStatus($id)
    {
        //Lấy danh sách trang kể cả xóa tạm
        $page = Page::withTrashed()->where('id', $id)->get();
        foreach ($page as $value) {
            //Nếu là trạng thái chờ duyệt thì khôi phục lại từ trash cập nhập lại giá trị
            if ($value->status != "Công khai") {
                Page::onlyTrashed()->where('id', $id)->restore();
                Page::where('id', $id)->update(['status' => 'Công khai']);
            } else {
                //Ngược lại thì xóa tạm thay đổi thành chờ duyệt
                Page::where('id', $id)->update(['status' => 'Chờ duyệt']);
                Page::withoutTrashed()->where('id', $id)->delete();
            }
            break;
        }
        //Chuyển hướng khi xóa xong kèm thông báo
        return redirect('admin/pages/list')->with('status', 'Thay đổi trạng thái trang thành công');
    }

    //Phương thức xóa vĩnh viễn trang
    function delete($id)
    {
        //Xóa vĩnh viễn trạng theo id đã chọn
        Page::where('id', $id)->forceDelete();
        //Chuyển hướng khi xóa xong kèm thông báo
        return redirect('admin/pages/list')->with('status', 'Xóa trang thành công');
    }
}
