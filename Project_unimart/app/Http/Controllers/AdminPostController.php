<?php

namespace App\Http\Controllers;

use App\User;
use App\Post;
use App\PostCat;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; //Phải khai báo khi tạo model theo kiểu Query builder
use Illuminate\Support\Facades\Auth;


class AdminPostController extends Controller
{
    //=============Phần Danh mục bài viết================//

    //Phương thức khởi tạo
    function __construct()
    {
        //Kiểm tra trước khi được vào trang này phải có quyền là 1(Administrator) và quyền thứ 4(PagePostCat)
        $this->middleware('CheckRole4');
        //Tạo ràng buộc kèm tham số next
        $this->middleware(function ($request, $next) {
            // thêm key và value cho session
            session(['module_active' => 'post']);
            return $next($request);
        });
    }

    //Phương thức lấy danh mục bài viết đã được phân cấp đệ quy
    public function getCategoriespost()
    {
        //Lấy tất cả danh mục với điều kiện status = công khai nhóm theo danh mục giảm dần và tham gia vào bảng users để lấy được các field như tên người tạo
        $categories =  DB::table('post_cats')
            ->join('users', 'users.id', '=', 'post_cats.user_id')
            ->select('post_cats.*', 'users.name', 'post_cats.cat_name')
            ->where('status', 'Công khai')
            //Điểm khác nhau giữa hai cách đổ dữ liệu là nếu chờ duyệt vẫn được phân cấp còn nếu ko chờ duyệt thì cách đầu ko cho phân cấp vẫn được tạo nhưng cấp ngoài cùng cấp cha
            ->orwhere('status', 'Chờ duyệt')
            ->orderBy('parent_id', 'DESC')
            ->get();
        //Hàm đệ quy được tạo ra ở helpers phân cấp cha con
        $recursives = data_tree($categories, $parent_id = 0, $level = 0);
        //Trả lại đệ quy đã phân cấp ở model
        return $recursives;
    }

    //Phương thức thêm danh mục bài viết mới
    function addCat(Request $request)
    {
        //Số lượng bài viết theo danh mục lấy ra tổng số lượng bài viết và nhóm lại theo danh mục
        $num_post_by_cat = DB::table('posts')
            ->selectRaw("Count('id') as number_posts, cat_id")
            ->groupBy('cat_id')
            ->orderBy('number_posts', 'DESC')
            ->get();
        //Sử dụng lấy danh mục bài viết đã phân cấp
        $list_cats = $this->getCategoriesPost();
        //return $categorys;
        return view('admin.posts.cat', compact('list_cats', 'num_post_by_cat'));
    }

    //Phương thức thêm danh mục bài viết mới validate trước khi lưu vào db
    function storeCat(Request $request)
    {
        //Chuẩn hóa dữ liệu để gửi dữ liệu vào db
        $request->validate(
            //Quy tắt yêu cầu dữ liệu
            //Lưu ý: Nếu để các quy tắc cách nhau bởi dấy phẩy theo quy tắc đăng ký user của laravel thì quy tắc sau ko hoạt động, phải để theo quy tắc cách nhau |
            [
                'cat_name' => 'required|string|unique:post_cats',
                'parent_id' => 'required'
            ],
            //Cấu hình thông báo lỗi
            [
                'required' => ':attribute không được để trống',
                'string' => ':attribute phải có dạng chuỗi',
                'unique' => ':attribute đã tồn tại trong hệ thống database',
            ],
            //Thay đổi field thành nội dung tiếng việt
            [
                'cat_name' => 'Tên danh mục',
                'parent_id' => 'Phải chọn danh mục thuộc danh mục'
            ]
        );
        //Tên slug bằng dữ liệu nhập tên danh mục chuyển thành chuỗi ko dấu ngăn cách bởi dấu chấm phẩy
        $slug_name = $request->input('cat_name');
        $slug = Str::slug($slug_name);
        $input['slug'] = $slug;
        //Thêm dữ liệu đã được validate vào database
        $post_cat = PostCat::create([
            'cat_name' => $request->input('cat_name'),
            'status' =>  $request->input('status'),
            'slug' => $input['slug'],
            'parent_id' => $request->input('parent_id'),
            'user_id' => Auth::id(),
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time())
        ]);
        //thì thông báo và chuyển hướng
        return redirect('admin/posts/cat/list')->with('status', 'Bạn đã thêm danh mục bài viết thành công');
    }

    //Phương thức hiển thị danh sách danh mục bài viết
    function listCat(Request $request)
    {
        //Số lượng bài viết theo danh mục lấy ra tổng số lượng bài viết và nhóm lại theo danh mục
        $num_post_by_cat = DB::table('posts')
            ->selectRaw("Count('id') as number_posts, cat_id")
            ->groupBy('cat_id')
            ->orderBy('number_posts', 'DESC')
            ->get();
        //Sử dụng join để lấy dữ liệu từ nhiều bảng khác nhau có sự liên kết lấy ra tên người đã tạo ra danh mục
        $list_cats = DB::table('post_cats')
            ->join('users', 'users.id', '=', 'post_cats.user_id')
            ->select('post_cats.*', 'users.name', 'post_cats.cat_name')
            ->get();
        //Sử dụng lấy danh mục bài viết đã phân cấp
        $list_cats = $this->getCategoriesPost();
        // return $list_cats;
        //Trả lại cho view với những dữ liệu trên để hiển thị
        return view('admin.posts.cat', compact('num_post_by_cat', 'list_cats'));
    }

    //Phương thức cập nhập danh mục bài viết
    function updateCat(Request $request, $id)
    {
        //Chuẩn hóa dữ liệu để gửi dữ liệu vào db
        $input = $request->all();
        //Quy tắt yêu cầu dữ liệu
        //Lưu ý: Nếu để các quy tắc cách nhau bởi dấy phẩy theo quy tắc đăng ký user của laravel thì quy tắc sau ko hoạt động, phải để theo quy tắc cách nhau |
        $request->validate(
            [
                'cat_name' => 'required|string|unique:post_cats',
            ],
            //Cấu hình thông báo lỗi
            [
                'required' => ':attribute không được để trống',
                'string' => ':attribute phải có dạng chuỗi',
                'unique' => ':attribute đã tồn tại trong hệ thống database',
            ],
            //Thay đổi field thành nội dung tiếng việt
            [
                'cat_name' => 'Danh mục bài viết',
            ]
        );
        //Tên slug bằng dữ liệu nhập tên danh mục chuyển thành chuỗi ko dấu ngăn cách bởi dấu chấm phẩy
        $slug_name = $request->input('cat_name');
        $slug = Str::slug($slug_name);
        $input['slug'] = $slug;

        //Từ model posts cập nhập dữ liệu lưu vào database
        PostCat::where('id', $id)
            ->update([
                'cat_name' => $request->input('cat_name'),
                'slug' => $input['slug'],
                'user_id' => Auth::id(),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ]);
        //Ngược lại thì thông báo và chuyển hướng
        return redirect('admin/posts/cat/list')->with('status', 'Cập nhật danh mục bài viết thành công');
    }

    //Phương thức cập nhập danh mục bài viết
    function editCat($id)
    {
        //Số lượng bài viết theo danh mục lấy ra tổng số lượng bài viết và nhóm lại theo danh mục
        $num_post_by_cat = DB::table('posts')
            ->selectRaw("Count('id') as number_posts, cat_id")
            ->groupBy('cat_id')
            ->orderBy('number_posts', 'DESC')
            ->get();
        //Tìm kiếm trang theo id
        $editcat = DB::table('post_cats')->find($id);
        //Lấy ra tất cả danh mục hiện có từ table trang
        $cats = DB::table('post_cats')
            ->get()
            ->unique('cat_id');
        //Sử dụng lấy danh mục bài viết đã phân cấp
        $list_cat = $this->getCategoriesPost();
        //Trả dữ liệu được lấy ra từ table của db về trang chỉnh sửa trang view
        return view('admin.posts.editcat', compact('num_post_by_cat', 'editcat', 'list_cat'));
    }

    //Phương thức thay đổi trạng thái danh mục bài viết
    function changeStatus($id)
    {
        //Lấy tất cả danh mục kể cả trong thùng rác đã xóa tạm thời
        $cat = PostCat::withTrashed()->where('id', $id)->get();
        // Duyệt qua các phần tử đó để lấy ra
        foreach ($cat as $value) {
            //Truy xuất vào phần tử trạng thái nếu có value chờ duyệt
            if ($value->status == 'Chờ duyệt') {
                //Và thay đổi value thành công khai
                PostCat::where('id', $id)
                    ->update(['status' => 'Công khai']);
                //Nếu chọn danh mục theo id có trạng thái công khai thì cập nhập nhập lại value chờ duyệt
            } else {
                PostCat::where('id', $id)
                    ->update(['status' => 'Chờ duyệt']);
            }
            //Dừng vòng lặp
            break;
        }
        return redirect('admin/posts/cat/list')->with('status', 'Thay đổi trạng thái danh mục bài viết thành công');
    }

    // Phương thức xóa danh mục bài viết vĩnh viễn
    function deleteCat($id)
    {
        //Lấy ra bài viết nào đó bất kì thuộc danh mục đã chọn xóa
        $list_post = Post::where('cat_id', $id)->get();
        //Nếu bài viết có tồn tại trong danh mục nào đó cần xóa
        if ($list_post->count() > 0) {
            //Thì ko cho phép xóa vì kèm cả bài viết
            return redirect('admin/posts/cat/list')->with('status', 'Không thể xóa danh mục bài viết này vì có bài viết kèm theo');
            //Ngược lại nếu danh mục chưa có bài viết thì cho xóa
        } else {
            PostCat::find($id)->forceDelete();
            return redirect('admin/posts/cat/list')->with('status', 'Xóa thành công danh mục bài viết');
        }
    }

    //=============Phần bài viết================//

    //Phương thức thêm bài viết mới
    function add(Request $request)
    {
        //Xử lí lấy dữ liệu danh mục bài viết để chuyển qua view
        $list_cats = DB::table('post_cats')
            ->where('status', 'Công khai')
            ->get();
        // return $list_cats;
        return view('admin.posts.add', compact('list_cats'));
    }

    //Phương thức thêm bài viết mới validate trước khi lưu vào db
    function store(Request $request)
    {
        //Chuẩn hóa dữ liệu để gửi dữ liệu vào db
        $request->validate(
            //Quy tắt yêu cầu dữ liệu
            //Lưu ý: Nếu để các quy tắc cách nhau bởi dấy phẩy theo quy tắc đăng ký user của laravel thì quy tắc sau ko hoạt động, phải để theo quy tắc cách nhau |
            [
                'title' => 'required|string|min:8|max:255|unique:posts',
                'content' => 'required|string',
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'cat' => 'required|max:50',
            ],
            //Cấu hình thông báo lỗi
            [
                'required' => ':attribute không được để trống',
                'string' => ':attribute phải có dạng chuỗi',
                'min' => ':attribute có độ dài ít nhất :min ký tự',
                'max' => ':attribute có độ dài tối đa :max ký tự',
                'unique' => ':attribute đã tồn tại trong hệ thống database',
                'file.required' => ':attribute ảnh bài viết',
                'image' => ':attribute ảnh có dạng file ảnh',
                'mimes' => ':attribute ảnh có đuôi dạng jpeg,png,jpg,gif,svg',
                'file.max' => ':attribute ảnh có dung lượng dưới 2048kb',
                'cat.required' => ':attribute danh mục bài viết'
            ],
            //Thay đổi field thành nội dung tiếng việt
            [
                'title' => 'Tiêu đề bài viết',
                'content' => 'Nội dung bài viết',
                'file' => 'File ảnh',
                'cat' => 'Danh mục bài viết',
            ]
        );

        //Lấy tất cả dữ liệu được nhập ra
        // return $request->all();

        //Nếu có file thì lấy ra các thông của file đó
        if ($request->hasFile('file')) {
            $file = $request->file;
            // echo $file;
            //Lấy tên của file định upload
            $filename = $file->getClientOriginalName();
            //Kiểm tra xem file đó ko có trong thư mục theo đường dẫn
            if (!file_exists('public/images/posts/' . $filename)) {
                //Thì di chuyển vào đường dẫn (trong folder public/uploads)
                $path = $file->move('public/images/posts', $file->getClientOriginalName());
                //Và đường dẫn của file mới lưu vào database
                // $thumbnail = 'public/images/posts/' . $filename;
                $thumbnail = 'images/posts/' . $filename;
            } else {
                //Nếu đã có tức là trùng thì đường dẫn của file sẽ tạo tên file mới với thời gian + tên file
                $newfilename = time() . '-' . $filename;
                //Di chuyển vào đường dẫn và đổi tên file đã tải lên thành tên file mới
                $path = $file->move('public/images/posts', $newfilename); //Chuyển file lên server(trong folder public/uploads)
                //Đường dẫn để lưu vào db sẽ là
                // $thumbnail = 'public/images/posts/' . $newfilename; //Đường dẫn của file lưu vào database
                $thumbnail = 'images/posts/' . $newfilename; //Đường dẫn của file lưu vào database
            }
            //Tạo trường thumbnail và thêm đường dẫn file vào
            $input['thumbnail'] = $thumbnail;
        }

        //Tên slug bằng dữ liệu nhập tên danh mục chuyển thành chuỗi ko dấu ngăn cách bởi dấu chấm phẩy
        $slug_title = $request->input('title');
        $slug = Str::slug($slug_title);
        $input['slug'] = $slug;

        //Từ model posts tạo dữ liệu lưu vào database
        $post = Post::create([
            'title' => $request->input('title'),
            'slug' => $input['slug'],
            'content' => $request->input('content'),
            'thumbnail' => $input['thumbnail'],
            'cat_id' => $request->input('cat'),
            'status' => $request->input('status'),
            'user_id' => Auth::id(),
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);

        //Nếu dữ liệu gửi lên có trạng thái chờ duyệt thì tìm kiếm trang theo id(trang đã thêm) đó xóa tạm để (thành trạng thái chờ duyệt khi thay đổi thì khôi phục lại và update thành công)
        if ($request->input('status') == "Chờ duyệt") {
            Post::find($post->id)->delete();
        }

        return redirect('admin/posts/list')->with('status', 'Bạn đã thêm bài viết mới thành công');
    }

    //Phương thức hiển thị danh sách bài viết
    function list(Request $request)
    {
        $keyword = "";
        if ($request->input('keyword')) {
            $keyword = $request->input('keyword');
        }
        //Số bài viết kích hoạt
        $num_post_active = Post::withoutTrashed()->count();
        // return $num_post_active;
        //Số bài viết vô hiệu hóa
        $num_post_disable = Post::onlyTrashed()->count();
        //Danh sách action kích hoạt
        $list_action_post = [
            'disable' => 'Vô hiệu hóa'
        ];
        //lấy giá trị trạng thái bài viết khi dữ liệu gửi lên
        $status_post = $request->input('status');
        //Nếu trạng thái là vô hiệu hóa thì hiển thị hai giá trị
        if ($status_post == "disable") {
            $list_action_post = [
                'restore' => 'Khôi phục',
                'delete' => 'Xóa vĩnh viễn'
            ];
            //Danh sách bài viết ở trạng thái vô hiệu hóa
            $list_posts = Post::onlyTrashed()
                ->join('users', 'users.id', '=', 'posts.user_id')
                ->join('post_cats', 'post_cats.id', '=', 'posts.cat_id')
                ->select('posts.*', 'users.name', 'post_cats.cat_name')
                ->paginate(20);
        } else {
        //Danh sách trạng thái kích hoạt
            $list_posts = Post::join('users', 'users.id', '=', 'posts.user_id')
                ->join('post_cats', 'post_cats.id', '=', 'posts.cat_id')
                ->select('posts.*', 'users.name', 'post_cats.cat_name')
                ->where('title', 'LIKE', "%{$keyword}%")
                ->orwhere('cat_name', 'LIKE', "%{$keyword}%")
                ->paginate(20);
        }
        return view('admin.posts.list', compact('list_posts', 'keyword', 'list_action_post', 'status_post', 'num_post_active', 'num_post_disable'));
    }

    //Phương thức cập nhập bài viết
    function update(Request $request, $id)
    {
        //Chuẩn hóa dữ liệu để gửi dữ liệu vào db
        $input = $request->all();

        //Quy tắt yêu cầu dữ liệu
        //Lưu ý: Nếu để các quy tắc cách nhau bởi dấy phẩy theo quy tắc đăng ký user của laravel thì quy tắc sau ko hoạt động, phải để theo quy tắc cách nhau |
        $request->validate(
            [
                'title' => 'required|string|min:8|max:255|unique:posts',
                'content' => 'required|string',
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'cat' => 'required|max:50',
            ],
            //Cấu hình thông báo lỗi
            [
                'required' => ':attribute không được để trống',
                'file.required' => ':attribute ảnh bài viết',
                'cat.required' => ':attribute danh mục bài viết',
                'string' => ':attribute phải có dạng chuỗi',
                'min' => ':attribute có độ dài ít nhất :min ký tự',
                'max' => ':attribute có độ dài tối đa :max ký tự',
                'unique' => ':attribute đã tồn tại trong hệ thống database',
                'image' => ':attribute ảnh có dạng file ảnh',
                'mimes' => ':attribute ảnh có đuôi dạng jpeg,png,jpg,gif,svg',
                'file.max' => ':attribute ảnh có dung lượng dưới 2048kb',
            ],
            //Thay đổi field thành nội dung tiếng việt
            [
                'title' => 'Tiêu đề bài viết',
                'content' => 'Nội dung bài viết',
                'file' => 'File ảnh',
                'cat' => 'Danh mục bài viết',
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
            if (!file_exists('public/images/posts/' . $filename)) {
                //Chuyển file lên server (trong folder public/uploads)
                $path = $file->move('public/images/posts', $file->getClientOriginalName());
                //Đường dẫn của file lưu vào database
                $thumbnail = 'images/posts/' . $filename;
            } else {
                $newfilename = time() . '-' . $filename;
                $path = $file->move('public/images/posts', $newfilename); //Chuyển file lên server(trong folder public/uploads)
                $thumbnail = 'images/posts/' . $newfilename; //Đường dẫn của file lưu vào database
            }
            //Tạo trường thumbnail và thêm đường dẫn file vào
            $input['thumbnail'] = $thumbnail;
        }

        //Xóa file ảnh
        $post = DB::table('posts')->find($id);
        if (!empty($post)) {
            @unlink($post->thumbnail);
        }

        //Tên slug bằng dữ liệu nhập tên danh mục chuyển thành chuỗi ko dấu ngăn cách bởi dấu chấm phẩy
        $slug_title = $request->input('title');
        $slug = Str::slug($slug_title);
        $input['slug'] = $slug;
        //Từ model pages cập nhập dữ liệu lưu vào database
        $post = Post::where('id', $id)
            ->update([
                'title' => $request->input('title'),
                'slug' => $input['slug'],
                'content' => $request->input('content'),
                'thumbnail' => $input['thumbnail'],
                'user_id' => Auth::id(),
                'cat_id' => $request->input('cat'),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ]);
        //Ngược lại thì thông báo và chuyển hướng
        return redirect('admin/posts/list')->with('status', 'Cập nhật bài viết thành công');
    }

    //Phương thức chỉnh sửa bài viết
    function edit($id)
    {
        //Tìm kiếm bài viết theo id
        $post = DB::table('posts')->find($id);
        //Xử lí thêm danh mục bài viết để chuyển qua view
        $list_cats = PostCat::all();
        //Lấy ra tất cả danh mục hiện có từ table trang
        $cat = DB::table('posts')
            ->get()
            ->unique('cat_id');
        //Trả dữ liệu được lấy ra từ table của db về trang chỉnh sửa trang view
        return view('admin.posts.edit', compact('cat', 'post', 'list_cats'));
    }

    //Phương thức khôi phục bài viết
    function restore($id)
    {
        //Lấy theo id bài viết trong thùng rác khôi phục lại
        Post::onlyTrashed()->where('id', $id)->restore();
        //Chuyển hướng khi xóa xong kèm thông báo
        return redirect('admin/posts/list')->with('status', 'Kích hoạt lại bài viết thành công');
    }

    //Phương thức vô hiệu hóa bài viết
    function disable($id)
    {
        //Tìm bài viết theo id và xóa tạm
        Post::find($id)->delete();
        //Chuyển hướng khi xóa xong kèm thông báo
        return redirect('admin/posts/list')->with('status', 'Vô hiệu hóa bài viết thành công');
    }

    //Phương thức xóa vĩnh viễn bài viết
    function delete($id)
    {
        //Tìm kiếm bài viết trong thùng rác (đã bị vô hiệu hóa) xóa vinh viễn chuyển hướng kèm thông báo
        Post::onlyTrashed()->where('id', $id)->forceDelete();
        return redirect('admin/posts/list')->with('status', 'Xóa vĩnh viễn bài viết thành công');
    }

    //Phương thức thực hiện tác vụ check
    function action(Request $request)
    {
        //Kiểm tra nếu có giá trị list_check = giá trị, action = giá trị action
        if ($request->input('list_check')) {
            //Danh sách check list
            $list_check = $request->input('list_check');
            //Hiển thị giá trị action
            $action = $request->input('action');
            //Nếu có action thì chia 3 trường hợp theo điều kiện
            if (!empty($action)) {
                switch ($action) {
                    //TH1: Nếu có giá trị là xóa thì xóa tạm cả trong lẫn ngoài thùng rác, chuyển hướng theo yêu cầu
                    case "disable":
                        Post::withoutTrashed()
                            ->whereIn('id', $list_check)
                            ->delete();
                    return redirect('admin/posts/list?status=disable')->with('status', 'Thực hiện vô hiệu hóa bài viết thành công');
                    break;
                    //TH2: Nếu có giá trị là khội phục thì khôi phục trong thùng rác và chuyển hướng theo yêu cầu
                    case "restore":
                        Post::onlyTrashed()
                            ->whereIn('id', $list_check)
                            ->restore();
                    return redirect('admin/posts/list')->with('status', 'Khôi phục bài viết thành công');
                    break;
                    //TH3: Nếu có giá trị là xóa trong thùng rác thì xóa vĩnh viễn trong thùng rác và chuyển hướng theo yêu cầu
                    case "delete":
                        Post::onlyTrashed()
                            ->whereIn('id', $list_check)
                            ->forceDelete();
                    return redirect('admin/posts/list?status=disable')->with('status', 'Xóa vĩnh viễn bài viết thành công');
                    break;
                }
            } else {
                //Nếu trong danh sách check trống (không có tác vụ) thì chuyển hướng ra dach sách trang hiện có và hiển thị thông báo
                return redirect('admin/posts/list')->with('status', 'Bạn phải chọn hình thức vô hiệu hóa, xóa vĩnh viễn hoặc kích hoạt lại');
            }
        } else {
            //Nếu trong danh sách check trống (không có tác vụ) thì chuyển hướng ra dach sách trang hiện có và hiển thị thông báo
            return redirect('admin/posts/list')->with('status', 'Bạn cần chọn thao tác cần thực thi');
        }
    }
}
