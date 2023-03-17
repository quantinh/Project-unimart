<?php

namespace App\Http\Controllers;

use App\Video;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdminVideoController extends Controller
{
    //Phương thức khởi tạo
    function __construct()
    {
        //Kiểm tra trước khi được vào trang này phải có quyền là 1(Administrator)
        $this->middleware('CheckRole5');
        //Tạo ràng buộc kèm tham số next
        $this->middleware(function ($request, $next) {
            //Thêm key và value cho session
            session(['module_active' => 'video']);
            return $next($request);
        });
    }

    //Phương thức thêm video mới
    function add(Request $request)
    {
        //Danh sách video
        $list_videos = Video::all();
        return view('admin.videos.add', compact('list_videos'));
    }

    //Phương thức thêm video mới validate trước khi lưu vào db
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
                'name_video' => 'required|string|unique:videos',
                'link' => 'required',
            ],
            //Cấu hình thông báo lỗi
            [
                'required' => ':attribute không được để trống',
                'string' => ':attribute phải có dạng chuỗi',
                'unique' => ':attribute đã tồn tại trong hệ thống database',
            ],
            //Thay đổi field thành nội dung tiếng việt
            [
                'name_video' => 'Tên video',
                'link' => 'link',
            ]
        );
        //Lấy tất cả dữ liệu được nhập ra
        // return $request->all();

        //Từ model video tạo dữ liệu lưu vào database
        video::create([
            'name_video' => $request->input('name_video'),
            'link' => $request->input('link'),
            'status' =>  $request->input('status'),
            'user_id' => Auth::id(),
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);
        //Chuyển hướng theo url của route thêm dữ liệu xong thì chuyển hướng tới trang hiển thị danh sách
        return redirect('admin/videos/list')->with('status', 'Bạn đã thêm video mới thành công');
    }

    //Phương thức hiển thị danh sách video
    function list(Request $request)
    {
        //Lấy danh sách trang kể cả trong thùng rác join users lấy field tên người tạo
        $list_videos = video::withTrashed()
        ->join('users', 'users.id', '=', 'videos.user_id')
        ->select('videos.*', 'users.name')
        ->paginate(5);
        //Trả lại cho view với những dữ liệu trên để hiển thị
        return view('admin.videos.list', compact('list_videos'));
    }

    //Phương thức cập nhập thông tin video
    function update(Request $request, $id)
    {
        //Chuẩn hóa dữ liệu để gửi dữ liệu vào db
        $input = $request->all();
        //Quy tắt yêu cầu dữ liệu
        $request->validate(
            //Quy tắt yêu cầu dữ liệu
            //Lưu ý: Nếu để các quy tắc cách nhau bởi dấy phẩy theo quy tắc đăng ký user của laravel thì quy tắc sau ko hoạt động, phải để theo quy tắc cách nhau |
            [
                'name_video' => 'required|string|unique:videos',
                'link' => 'required',
            ],
            //Cấu hình thông báo lỗi
            [
                'required' => ':attribute không được để trống',
                'string' => ':attribute phải có dạng chuỗi',
                'unique' => ':attribute đã tồn tại trong hệ thống database',
            ],
            //Thay đổi field thành nội dung tiếng việt
            [
                'name_video' => 'Tên video',
                'link' => 'link',
            ]
        );

        //Lấy tất cả dữ liệu được nhập ra
        // return $request->all();

        //Từ model video tạo dữ liệu lưu vào database
        Video::where('id', $id)
            ->update([
                'name_video' => $request->input('name_video'),
                'link' => $request->input('link'),
                'user_id' => Auth::id(),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ]);
        //Chuyển hướng theo url của route thêm dữ liệu xong thì chuyển hướng tới trang hiển thị danh sách
        return redirect('admin/videos/list')->with('status', 'Bạn đã cập nhập lại video thành công');
    }

    //Phương thức chỉnh sửa thông tin video
    function edit($id)
    {
        //Danh sách video
        $videos = Video::withTrashed()->find($id);
        //Chuyển dữ liệu từ db về qua view để thực hiện sửa thông tin
        return view('admin.videos.edit', compact('videos'));
    }

    //Phương thức thay đổi trạng thái video
    function changeStatus($id)
    {
        //Lấy danh sách video kể cả xóa tạm
        $video = Video::withTrashed()->where('id', $id)->get();
        foreach ($video as $value) {
            //Nếu là trạng thái chờ duyệt thì khôi phục lại từ trash cập nhập lại giá trị
            if ($value->status != "Công khai") {
                Video::onlyTrashed()->where('id', $id)->restore();
                Video::where('id', $id)->update(['status' => 'Công khai']);
            } else {
                //Ngược lại thì xóa tạm thay đổi thành chờ duyệt
                Video::where('id', $id)->update(['status' => 'Chờ duyệt']);
                Video::withoutTrashed()->where('id', $id)->delete();
            }
            break;
        }
        //Chuyển hướng khi xóa xong kèm thông báo
        return redirect('admin/videos/list')->with('status', 'Thay đổi trạng thái video thành công');
    }

    //Phương thức xóa vĩnh viễn video
    function delete($id)
    {
        Video::where('id', $id)->forceDelete();
        //Chuyển hướng khi xóa xong kèm thông báo
        return redirect('admin/videos/list')->with('status', 'Xóa video thành công');
    }
}
