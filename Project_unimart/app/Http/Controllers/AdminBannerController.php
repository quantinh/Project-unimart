<?php

namespace App\Http\Controllers;

use App\User;
use App\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; //Phải khai báo khi tạo model theo kiểu Query builder

class AdminBannerController extends Controller
{
    //=============Phần Banner================//

    //Phương thức khởi tạo
    function __construct()
    {
        //Kiểm tra trước khi được vào trang này phải có quyền là 1(Administrator) và quyền thứ 3(SliderBanner)
        $this->middleware('CheckRole3');
        //Tạo ràng buộc kèm tham số next
        $this->middleware(function ($request, $next) {
            // thêm key và value cho session
            session(['module_active' => 'banner']);
            return $next($request);
        });
    }

    //Phương thức thêm banners
    function add(Request $request)
    {
        //Đếm tổng số banner có trạng thái công khai
        $num_open = Banner::where('status', 'Công khai')->count();
        //Đếm tổng số banner có trạng thái chờ duyệt
        $num_wait = Banner::where('status', 'Chờ duyệt')->count();
        //Nếu value url có trạng thái chờ duyệt thì xét cho wait = 1 để đánh dấu
        if ($request->input('status') == 'Chờ duyệt') {
            $wait = 1;
            //Xếp ngày tháng cập nhập theo thứ tự mới nhất rồi lấy ra theo get
            $banners = Banner::orderby('updated_at', 'desc')
                ->join('users', 'users.id', '=', 'banners.user_id')
                ->select('banners.*', 'users.name')
                ->where('status', 'Chờ duyệt')
                ->get();
            //Trả về view những dữ liệu cần thiết nếu là trạng thái chờ duyệt
            return view('admin.banners.list', compact('num_open', 'num_wait', 'wait',  'banners'));
        }
        //Sắp xếp position theo thứ tự tăng dần của banner có trạng thái công khai
        $banners = Banner::orderby('position')
            ->join('users', 'users.id', '=', 'banners.user_id')
            ->select('banners.*', 'users.name')
            ->where('status', 'Công khai')
            ->get();
        //Trả về view những dữ liệu cần thiết
        return view('admin.banners.list', compact('num_open', 'num_wait', 'banners'));
    }

    //Phương thức thêm banners mới validate trước khi lưu vào db
    function store(Request $request)
    {
        //Chuẩn hóa dữ liệu để gửi dữ liệu vào db
        $request->validate(
            //Quy tắt yêu cầu dữ liệu
            //Lưu ý: Nếu để các quy tắc cách nhau bởi dấy phẩy theo quy tắc đăng ký user của laravel thì quy tắc sau ko hoạt động, phải để theo quy tắc cách nhau |
            [
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            ],
            //Cấu hình thông báo lỗi
            [
                'required' => ':attribute không được để trống',
                'image' => ':attribute ảnh có dạng file ảnh',
                'mimes' => ':attribute ảnh có đuôi dạng jpeg,png,jpg,gif,svg',
            ],
            //Thay đổi field thành nội dung tiếng việt
            [
                'file' => 'Phải chọn ảnh quảng cáo'
            ]
        );
        //Lấy tất cả dữ liệu được nhập ra
        //return $request->all();

        //Nếu có file thì xuất các thông của file
        if ($request->hasFile('file')) {
            $file = $request->file;
            //Lấy tên file
            $filename = $file->getClientOriginalName();
            if (!file_exists('public/images/banners/' . $filename)) {
                //Chuyển file lên server (trong folder public/uploads)
                $path = $file->move('public/images/banners', $file->getClientOriginalName());
                //Đường dẫn của file lưu vào database
                $thumbnail = 'images/banners/' . $filename;
            } else {
                $newfilename = time() . '-' . $filename;
                $path = $file->move('public/images/banners', $newfilename); //Chuyển file lên server(trong folder public/uploads)
                $thumbnail = 'images/banners/' . $newfilename; //Đường dẫn của file lưu vào database
            }
            //Tạo trường thumbnail và thêm đường dẫn file vào
            $input['image'] = $thumbnail;
        }
        //Đếm tổng banner có trạng thái công khai
        $num_open = Banner::where('status', 'Công khai')->count();
        //Đếm tổng banner có trạng thái chờ duyệt
        $num_waiting = Banner::where('status', 'Chờ duyệt')->count();
        //Nếu dữ liệu truy vấn trả về có giá trị công khai thì cộng thêm 1 ngược lại thì bằng 0
        if ($request->input('status') == 'Công khai') {
            $position = $num_open + 1;
        } else {
            $position = 0;
        }
        //Thêm dữ liệu đã được validate vào database
        $banners = Banner::create([
            //Để insert vào db thì chỗ này phải bằng field của db không được khác validate hay .blade.php khác cũng được
            'image' => $input['image'],
            'status' =>  $request->input('status'),
            'user_id' => Auth::id(),
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
            'position' => $position,
        ]);
        // return $banners;
        //thông báo và chuyển hướng
        return redirect('admin/banners/list')->with('status', 'Bạn đã thêm ảnh banner mới thành công');
    }

    //Phương thức hiển thị danh sách banners hiện có
    function list(Request $request)
    {
        //Đếm tổng số banner có trạng thái công khai
        $num_open = Banner::where('status', 'Công khai')->count();
        //Đếm tổng số banner có trạng thái chờ duyệt
        $num_wait = Banner::where('status', 'Chờ duyệt')->count();
        //Nếu value url có trạng thái chờ duyệt thì xét cho wait = 1 để đánh dấu
        if ($request->input('status') == 'Chờ duyệt') {
            $wait = 1;
            //Xếp ngày tháng cập nhập theo thứ tự mới nhất rồi lấy ra theo get
            $banners = Banner::orderby('updated_at', 'desc')
                ->join('users', 'users.id', '=', 'banners.user_id')
                ->select('banners.*', 'users.name')
                ->where('status', 'Chờ duyệt')
                ->paginate(5);
            //Trả về view những dữ liệu cần thiết nếu là trạng thái chờ duyệt
            return view('admin.banners.list', compact('num_open', 'num_wait', 'wait',  'banners'));
        }
        //Sắp xếp position theo thứ tự tăng dần của banner có trạng thái công khai
        $banners = Banner::orderby('position')
            ->join('users', 'users.id', '=', 'banners.user_id')
            ->select('banners.*', 'users.name')
            ->where('status', 'Công khai')
            ->paginate(5);
        //Trả về view những dữ liệu cần thiết
        return view('admin.banners.list', compact('num_open', 'num_wait', 'banners'));
    }

    //Phương thức di chuyển vị trí banners lên trên
    function up($id)
    {
        //Tìm kiếm Banner theo id đã chọn di chuyển lên
        $Banner_current = Banner::find($id);
        //Từ id truy xuất được field 'position' có giá trị và trừ đi 1 và cập nhập giá trị bị trừ vào db
        Banner::where('position', $Banner_current['position'] - 1)
            ->update([
                'position' => $Banner_current['position'],
            ]);
        //Điều kiện cập nhập là trường id phải tồn tại với (id hiện tại giống id trong csdl) khi cập nhập thì id hiện tại - 1 và insert vào db
        Banner::where('id', $id)
            ->update([
                'position' => $Banner_current['position'] - 1,
            ]);
        // return $Banner_current['position']-1;
        return redirect('admin/banners/list')->with('status', 'Vị trí hiển thị ảnh quảng cáo đã được thay đổi lên trên');
    }

    //Phương thức di chuyển vị trí banners xuống dưỡi
    function down($id)
    {
        //Tìm kiếm banner theo id
        $banner_current = Banner::find($id);
        //Từ id truy xuất được field position và trừ đi 1 cho phần tử hiện tại và cập nhập vào db
        Banner::where('position', $banner_current['position'] + 1)
            ->update([
                'position' => $banner_current['position'],
            ]);
        //Điều kiện cập nhập là trường id phải tồn tại với id hiện tại giống id trong csdl khi cập nhập thì id hiện tại trừ đi 1 và insert vào db
        Banner::where('id', $id)
            ->update([
                'position' => $banner_current['position'] + 1,
            ]);
        return redirect('admin/banners/list')->with('status', 'Vị trí hiển thị ảnh quảng cáo đã được thay đổi xuống dưới');
    }

    //Phương thức thay đổi trạng thái banners
    function changeStatus($id)
    {
        //Tìm id đang thay đổi trạng thái
        $banner_current = Banner::find($id);
        //Đếm tổng những banner công khai sau đó cộng thêm một khi thay đổi từ chờ duyệt sang công khai
        $num_open = Banner::where('status', 'Công khai')->count();
        //Đếm tổng những banner công khai sau đó xét = 0 khi thay đổi từ công khai sang chờ duyệt
        $num_wait = Banner::where('status', 'Chờ duyệt')->count();
        //Truy xuất vào phần tử trạng thái nếu có value chờ duyệt
        if ($banner_current->status == 'Chờ duyệt') {
            //Thay đổi value thành công khai
            Banner::where('id', $id)
                ->update(
                    [
                        'status' => 'Công khai',
                        'position' => $num_open + 1
                    ]
                );
        //Nếu trạng thái công khai thì cập nhập nhập lại value chờ duyệt
        } else {
            Banner::where('id', $id)
                ->update(
                    [
                        'status' => 'Chờ duyệt',
                        'position' => 0
                    ]
                );
        }
        return redirect('admin/banners/list')->with('status', 'Thay đổi trạng thái ảnh quảng cáo thành công');
    }

    //Phương thức xóa banners vĩnh viễn
    function delete(Request $request, $id)
    {
        //Xử lí xóa banner tìm kiếm theo id
        $file_banner = DB::table('banners')->find($id);
        //Kiểm tra nếu file ảnh banner (đường dẫn file) tồn tại
        if (file_exists($file_banner->image)) {
            //Thì xóa đường dẫn ảnh
            @unlink($file_banner->image);
        }
        //Tìm banner theo id và xóa đi
        $banner = Banner::where('id', $id)->Delete(); //Vì có xóa tạm trong ngăn nên sử dụng forceDelete
        //Đếm số thứ tự những banner công khai
        $num_open = Banner::where('status', 'Công khai')->count();
        //Đếm số thứ tự những banner chờ duyệt
        $num_waiting = Banner::where('status', 'Chờ duyệt')->count();
        //Nếu dữ liệu truy vấn trả về có giá trị công khai thì trừ 1 ngược lại thì bằng 0
        if ($request->input('status') == 'Công khai') {
            $position = $num_open - 1;
        } else {
            $position = 0;
        }
        //Kiểm tra id xem nếu id đó là xóa công khai thì xóa xong cập nhập lại sốt thứ tự, còn nếu xóa chờ duyệt thì xét bằng 0
        Banner::where('id', $id)
            ->update([
                'position' => $position,
            ]);
        //Chuyển hướng khi xóa xong kèm thông báo
        return redirect('admin/banners/list')->with('status', 'Xóa ảnh quảng cáo thành công');
    }
}
