<?php

namespace App\Http\Controllers;

use App\User;
use App\Product;
use App\ProductCat;
use App\ProductImage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; //Phải khai báo khi tạo model theo kiểu Query builder

class AdminProductController extends Controller
{
    //=============Phần danh mục sản phẩm================//

    //Phương thức khởi tạo
    function __construct()
    {
        //Kiểm tra trước khi được vào trang này phải có quyền là 1(Administrator) và quyền thứ 2(ProductOrder)
        $this->middleware('CheckRole2');
        //Tạo ràng buộc kèm tham số next
        $this->middleware(function ($request, $next) {
            // thêm key và value cho session
            session(['module_active' => 'product']);
            return $next($request);
        });
    }

    //Phương thức lấy danh mục sản phầm đã được phân cấp đệ quy
    public function getCategoriesproduct()
    {
        //Lấy tất cả danh mục với điều kiện status = công khai nhóm theo danh mục giảm dần và tham gia vào bảng users để lấy được các field như tên người tạo
        $categories =  DB::table('product_cats')
            ->join('users', 'users.id', '=', 'product_cats.user_id')
            ->select('product_cats.*', 'users.name', 'product_cats.cat_name')
            ->where('status', 'Công khai')
            // ->orderBy('parent_id', 'DESC')
            //Điểm khác nhau giữa hai cách đổ dữ liệu là nếu chờ duyệt vẫn được phân cấp còn nếu ko chờ duyệt thì cách đầu ko cho phân cấp vẫn được tạo nhưng cấp ngoài cùng cấp cha
            ->orwhere('status', 'Chờ duyệt')
            ->get();
        $recursives = data_tree($categories, $parent_id = 0, $level = 0);
        //Trả lại đệ quy đã phân cấp ở model
        return $recursives;
    }

    //Phương thức thêm danh mục sản phẩm mới
    function addCat(Request $request)
    {
        //Số lượng sản phẩm theo danh mục lấy ra tổng số lượng sản phẩm và nhóm lại theo danh mục
        $num_product_by_cat = DB::table('products')
            ->selectRaw("Count('id') as number_products, cat_id")
            ->groupBy('cat_id')
            ->orderBy('number_products', 'DESC')
            ->get();
        //Sử dụng lấy danh mục sản phẩm đã phân cấp
        $list_cat = $this->getCategoriesproduct();
        //return $categorys;
        return view('admin.products.cat', compact('num_product_by_cat', 'list_cat'));
    }

    //Phương thức thêm danh mục sản phẩm mới validate trước khi lưu vào db
    function storeCat(Request $request)
    {
        //Chuẩn hóa dữ liệu để gửi dữ liệu vào db
        $request->validate(
            //Quy tắt yêu cầu dữ liệu
            //Lưu ý: Nếu để các quy tắc cách nhau bởi dấy phẩy theo quy tắc đăng ký user của laravel thì quy tắc sau ko hoạt động, phải để theo quy tắc cách nhau |
            [
                'cat_name' => 'required|string|unique:product_cats',
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
                'parent_id' => 'required'
            ],
            //Cấu hình thông báo lỗi
            [
                'required' => ':attribute không được để trống',
                'string' => ':attribute phải có dạng chuỗi',
                'unique' => ':attribute đã tồn tại trong hệ thống database',
                'image' => ':attribute ảnh có dạng file ảnh',
                'mimes' => ':attribute ảnh có đuôi dạng jpeg,png,jpg,gif,svg',
            ],
            //Thay đổi field thành nội dung tiếng việt
            [
                'cat_name' => 'Tên danh mục',
                'file' => 'Icon danh mục',
                'parent_id' => 'Phải chọn danh mục thuộc danh mục'
            ]
        );
        //Nếu có file thì xuất các thông của file
        if ($request->hasFile('file')) {
            $file = $request->file;
            // echo $file;
            //Lấy tên file
            $filename = $file->getClientOriginalName();
            if (!file_exists('public/images/icons/' . $filename)) {
                //Chuyển file lên server (trong folder public/uploads)
                $path = $file->move('public/images/icons', $file->getClientOriginalName());
                //Đường dẫn của file lưu vào database
                $thumbnail = 'images/icons/' . $filename;
            } else {
                $newfilename = time() . '-' . $filename;
                $path = $file->move('public/images/icons', $newfilename); //Chuyển file lên server(trong folder public/uploads)
                $thumbnail = 'images/icons/' . $newfilename; //Đường dẫn của file lưu vào database
            }
            //Tạo trường thumbnail và thêm đường dẫn file vào
            $input['image'] = $thumbnail;
        }
        //Tên slug bằng dữ liệu nhập tên danh mục chuyển thành chuỗi ko dấu ngăn cách bởi dấu chấm phẩy
        $slug_name = $request->input('cat_name');
        $slug = Str::slug($slug_name);
        $input['slug'] = $slug;
        //Thêm dữ liệu đã được validate vào database
        $product_cat = ProductCat::create([
            'cat_name' => $request->input('cat_name'),
            'status' =>  $request->input('status'),
            'slug' => $input['slug'],
            'icon_cat' => $input['image'],
            'parent_id' => $request->input('parent_id'),
            'user_id' => Auth::id()
        ]);
        //thì thông báo và chuyển hướng
        return redirect('admin/products/cat/list')->with('status', 'Bạn đã thêm danh mục sản phẩm thành công');
    }

    //Phương thức hiển thị danh sách danh mục sản phẩm
    function listCat(Request $request)
    {
        //Số lượng sản phẩm theo danh mục lấy ra tổng số lượng sản phẩm và nhóm lại theo danh mục
        $num_product_by_cat = DB::table('products')
            ->selectRaw("Count('id') as number_products, cat_id")
            ->groupBy('cat_id')
            ->orderBy('number_products', 'DESC')
            ->get();
        // return $num_product_by_cat;
        // Sử dụng join để lấy dữ liệu từ nhiều bảng khác nhau có sự liên kết lấy ra tên người đã tạo ra danh mục
        $list_cats = DB::table('product_cats')
            ->join('users', 'users.id', '=', 'product_cats.user_id')
            ->select('product_cats.*', 'users.name', 'product_cats.cat_name')
            ->get();
        //Sử dụng lấy danh mục sản phẩm đã phân cấp
        $list_cat = $this->getCategoriesproduct();
        // return $list_cat;
        // Trả lại cho view với những dữ liệu trên để hiển thị
        return view('admin.products.cat', compact('num_product_by_cat', 'list_cat'));
    }

    //Phương thức cập nhập danh mục sản phẩm
    function updateCat(Request $request, $id)
    {
        //Chuẩn hóa dữ liệu để gửi dữ liệu vào db
        $input = $request->all();
        //Quy tắt yêu cầu dữ liệu
        //Lưu ý: Nếu để các quy tắc cách nhau bởi dấy phẩy theo quy tắc đăng ký user của laravel thì quy tắc sau ko hoạt động, phải để theo quy tắc cách nhau |
        $request->validate(
            [
                'cat_name' => 'required|string|unique:product_cats',
                'cat_icon' => 'required'

            ],
            //Cấu hình thông báo lỗi
            [
                'required' => ':attribute không được để trống',
                'string' => ':attribute phải có dạng chuỗi',
                'unique' => ':attribute đã tồn tại trong hệ thống database',
            ],
            //Thay đổi field thành nội dung tiếng việt
            [
                'cat_name' => 'Danh mục sản phẩm',
                'cat_icon' => 'Icon danh mục'
            ]
        );
        //Nếu có file thì xuất các thông của file
        if ($request->hasFile('file')) {
            $file = $request->file;
            //Lấy tên file
            $filename = $file->getClientOriginalName();
            if (!file_exists('public/images/icons/' . $filename)) {
                //Chuyển file lên server (trong folder public/uploads)
                $path = $file->move('public/images/icons', $file->getClientOriginalName());
                //Đường dẫn của file lưu vào database
                $thumbnail = 'images/icons/' . $filename;
            } else {
                $newfilename = time() . '-' . $filename;
                $path = $file->move('public/images/icons', $newfilename); //Chuyển file lên server(trong folder public/uploads)
                $thumbnail = 'images/icons/' . $newfilename; //Đường dẫn của file lưu vào database
            }
            //Tạo trường thumbnail và thêm đường dẫn file vào
            $input['image'] = $thumbnail;
        }
        //Xóa file ảnh
        $product_cat = DB::table('product_cats')->find($id);
        if (!empty($product_cat)) {
            @unlink($product_cat->icon_cat);
        }
        //Tên slug bằng dữ liệu nhập tên danh mục chuyển thành chuỗi ko dấu ngăn cách bởi dấu chấm phẩy
        $slug_name = $request->input('cat_name');
        $slug = Str::slug($slug_name);
        $input['slug'] = $slug;
        //Từ model products cập nhập dữ liệu lưu vào database
        DB::table('product_cats')
            ->where('id', $id)
            ->update([
                'cat_name' => $request->input('cat_name'),
                'slug' => $input['slug'],
                'icon_cat' => $input['image'],
                'user_id' => Auth::id(),
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ]);
        //Ngược lại thì thông báo và chuyển hướng
        return redirect('admin/products/cat/list')->with('status', 'Cập nhật danh mục sản phẩm thành công');
    }

    //Phương thức cập nhập danh mục sản phẩm
    function editCat($id)
    {
        //Số lượng sản phẩm theo danh mục lấy ra tổng số lượng sản phẩm và nhóm lại theo danh mục
        $num_product_by_cat = DB::table('products')
            ->selectRaw("Count('id') as number_products, cat_id")
            ->groupBy('cat_id')
            ->orderBy('number_products', 'DESC')
            ->get();
        //Tìm kiếm trang theo id
        $edit_cat = DB::table('product_cats')->find($id);
        //Lấy ra tất cả danh mục hiện có từ table trang
        $cats = DB::table('product_cats')
            ->get()
            ->unique('cat_id');
        //Sử dụng lấy danh mục sản phẩm đã phân cấp
        $list_cat = $this->getCategoriesproduct();
        //Trả dữ liệu được lấy ra từ table của db về trang chỉnh sửa trang view
        return view('admin.products.editcat', compact('num_product_by_cat', 'edit_cat', 'list_cat'));
    }

    //Phương thức thay đổi trạng thái danh mục sản phẩm
    function changeStatus($id)
    {
        //Lấy tất cả danh mục kể cả trong thùng rác đã xóa tạm thời
        $cat = ProductCat::withTrashed()->where('id', $id)->get();
        // return $cat;
        // Duyệt qua các phần tử đó để lấy ra
        foreach ($cat as $value) {
            //Truy xuất vào phần tử trạng thái nếu có value chờ duyệt
            if ($value->status == 'Chờ duyệt') {
                //Và thay đổi value thành công khai
                ProductCat::where('id', $id)
                    ->update(['status' => 'Công khai']);
                //Nếu chọn danh mục theo id có trạng thái công khai thì cập nhập nhập lại value chờ duyệt
            } else {
                ProductCat::where('id', $id)
                    ->update(['status' => 'Chờ duyệt']);
            }
            //Dừng vòng lặp
            break;
        }
        return redirect('admin/products/cat/list')->with('status', 'Thay đổi trạng thái danh mục sản phẩm thành công');
    }

    //Phương thức xóa danh mục sản phẩm vĩnh viễn
    function deleteCat($id)
    {
        //Lấy ra sản phẩm nào đó bất kì thuộc danh mục đã chọn
        $list_product = Product::where('cat_id', $id)->get();
        // return $list_product;

        //Nếu sản phẩm có tồn tại trong danh mục nào đó cần xóa
        if ($list_product->count() > 0) {
            //Thì ko cho phép xóa vì kèm cả sản phẩm
            return redirect('admin/products/cat/list')->with('status', 'Không thể xóa danh mục sản phẩm này vì có sản phẩm kèm theo');
            //Ngược lại nếu danh mục chưa có sản phẩm
        } else {
            //Thì được xóa vĩnh viễn danh mục theo id đã chọn
            ProductCat::find($id)->forceDelete();
            return redirect('admin/products/cat/list')->with('status', 'Xóa danh mục sản phẩm thành công');
        }
    }

    //=============Phần danh mục thương hiệu================//

    //Phương thức thêm danh mục sản phẩm mới
    function addBrand(Request $request)
    {
        //Số lượng sản phẩm theo danh mục lấy ra tổng số lượng sản phẩm và nhóm lại theo danh mục
        $brands = DB::table('product_brands')->paginate();
        return view('admin.products.brand', compact('brands'));
    }

    //Phương thức thêm danh mục thương hiệu mới validate trước khi lưu vào db
    function storeBrand(Request $request)
    {
        //Chuẩn hóa dữ liệu để gửi dữ liệu vào db
        $request->validate(
            //Quy tắt yêu cầu dữ liệu
            //Lưu ý: Nếu để các quy tắc cách nhau bởi dấy phẩy theo quy tắc đăng ký user của laravel thì quy tắc sau ko hoạt động, phải để theo quy tắc cách nhau |
            [
                'name_brand' => 'required|string|unique:product_brands',
            ],
            //Cấu hình thông báo lỗi
            [
                'required' => ':attribute không được để trống',
                'string' => ':attribute phải có dạng chuỗi',
                'unique' => ':attribute đã tồn tại trong hệ thống database',
            ],
            //Thay đổi field thành nội dung tiếng việt
            [
                'name_brand' => 'Tên danh mục',
            ]
        );
        //Thêm dữ liệu đã được validate vào database
        $brands = DB::table('product_brands')->insert([
            'name_brand' => request()->name_brand,
            'user_id' => Auth::id(),
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time())
        ]);
        //Thì thông báo và chuyển hướng
        return redirect('admin/products/brand/add')->with('status', 'Bạn đã thêm thương hiệu mới thành công');
    }

    //Phương thức chỉnh sửa danh mục thương hiệu sản phẩm
    function editBrand($id)
    {
        $brand = DB::table('product_brands')->find($id);
        $brands = DB::table('product_brands')->get();
        return view('admin.products.editbrand', compact('brand', 'brands'));
    }

    //Phương thức cập nhập danh mục thương hiệu sản phẩm
    function updateBrand(Request $request, $id)
    {
        $request->validate(
            [
                'name_brand' => 'required|string|unique:product_brands',
            ],
            [
                'required' => ':attribute không được để trống',
                'max' => ':attribute có độ dài tối đa :max ký tự',
                'unique' => ':attribute đã tồn tại trong hệ thống database',
            ],
            [
                'name_brand' => 'Thương hiệu sản phẩm',
            ]
        );
        DB::table('product_brands')
            ->where('id', '=', $id)
            ->update([
                'name_brand' => request()->name_brand,
                'repairer' => Auth::id(),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ]);
        return redirect('admin/products/brand/add')->with('status', 'Cập nhật thương hiệu thành công');
    }

    //Phương thức Xóa vĩnh viễn thương hiệu sản phẩm
    function deleteBrand($id)
    {
        DB::table('product_brands')
            ->where('id', '=', $id)
            ->delete();
        return redirect('admin/products/brand/add')->with('status', 'Xóa vĩnh viễn thương hiệu sản phẩm thành công');
    }

    //=============Phần danh mục màu================//

    //Phương thức thêm màu mới
    function addColor(Request $request)
    {
        //Số lượng sản phẩm theo danh mục lấy ra tổng số lượng sản phẩm và nhóm lại theo danh mục
        $colors = DB::table('product_colors')->paginate(10);
        return view('admin.products.color', compact('colors'));
    }

    //Phương thức thêm danh mục thương hiệu mới validate trước khi lưu vào db
    function storeColor(Request $request)
    {
        //Chuẩn hóa dữ liệu để gửi dữ liệu vào db
        $request->validate(
            //Quy tắt yêu cầu dữ liệu
            //Lưu ý: Nếu để các quy tắc cách nhau bởi dấy phẩy theo quy tắc đăng ký user của laravel thì quy tắc sau ko hoạt động, phải để theo quy tắc cách nhau |
            [
                'name_color' => 'required|string|unique:product_colors',
                'color_select' => 'required',
            ],
            //Cấu hình thông báo lỗi
            [
                'required' => ':attribute không được để trống',
                'string' => ':attribute phải có dạng chuỗi',
                'unique' => ':attribute đã tồn tại trong hệ thống database',
            ],
            //Thay đổi field thành nội dung tiếng việt
            [
                'name_color' => 'Tên mã màu',
                'color_select' => 'Chọn màu',
            ]
        );
        // return $request->all();

        //Thêm dữ liệu đã được validate vào database
        $colors = DB::table('product_colors')->insert([
            'name_color' => request()->name_color,
            'code_color' => request()->color_select,
            'user_id' => Auth::id(),
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time())
        ]);
        //Thì thông báo và chuyển hướng
        return redirect('admin/products/color/add')->with('status', 'Bạn đã thêm mã màu mới thành công');
    }

    //Phương thức chỉnh sửa danh mục thương hiệu sản phẩm
    function editColor($id)
    {
        $color = DB::table('product_colors')->find($id);
        $colors = DB::table('product_colors')->get();
        return view('admin.products.editcolor', compact('color', 'colors'));
    }

    //Phương thức cập nhập danh mục thương hiệu sản phẩm
    function updateColor(Request $request, $id)
    {
        $request->validate(
            [
                'name_color' => 'required|string|unique:product_colors',
            ],
            [
                'required' => ':attribute không được để trống',
                'string' => ':attribute phải có dạng chuỗi',
                'unique' => ':attribute đã tồn tại trong hệ thống database',
            ],
            [
                'name_color' => 'Tên mã màu',
            ]
        );
        // return $request->all();
        $db = DB::table('product_colors')
            ->where('id', '=', $id)
            ->update([
                'name_color' => $request->input('name_color'),
                'code_color' => request()->color_select,
                'user_id' => Auth::id(),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ]);
        // return $db;
        return redirect('admin/products/color/add')->with('status', 'Cập nhật mã màu thành công');
    }

    //Phương thức Xóa vĩnh viễn thương hiệu sản phẩm
    function deleteColor($id)
    {
        DB::table('product_colors')
            ->where('id', '=', $id)
            ->delete();
        return redirect('admin/products/color/add')->with('status', 'Xóa vĩnh viễn mã màu thành công');
    }

    //=============Phần sản phẩm================//

    //Phương thức thêm sản phẩm mới
    function add(Request $request)
    {
        //Lấy tất cả các thương hiệu hiện có từ db
        $list_brands = DB::table('product_brands')
            ->get();
        //Lấy tất cả màu sản phẩm có trạng thái đang hoạt động
        $list_colors = DB::table('product_colors')
            ->get();
        //Lấy tất cả danh mục có trạng thái công khai
        $list_cats = DB::table('product_cats')
            ->where('status', 'Công khai')
            ->get();
        //Chuyển hướng khi thêm
        return view('admin.products.add', compact('list_cats', 'list_brands', 'list_colors'));
    }

    //Phương thức thêm sản phẩm mới validate trước khi lưu vào db
    function store(Request $request)
    {
        //Chuẩn hóa dữ liệu để gửi dữ liệu vào db
        $request->validate(
            //Quy tắt yêu cầu dữ liệu
            //Lưu ý: Nếu để các quy tắc cách nhau bởi dấy phẩy theo quy tắc đăng ký user của laravel thì quy tắc sau ko hoạt động, phải để theo quy tắc cách nhau |
            [
                'name_product' => 'required|string|max:255|unique:products',
                'price' => 'integer|required',
                'price_old' => 'integer',
                'quantily' => 'integer|required',
                'image' => 'required|image|mimes:jpeg,png,jpg',
                'image_desc.*' => 'required|image|mimes:jpeg,png,jpg',
                'brand_id' => 'required',
                'color_id' => 'required',
                'cat_id' => 'required',
                'detail' => 'required',
                'description' => 'required',
                'status' => 'required',
            ],
            //Cấu hình thông báo lỗi
            [
                'required' => ':attribute không được để trống',
                'string' => ':attribute phải có dạng chuỗi',
                'max' => ':attribute có độ dài tối đa :max ký tự',
                'unique' => ':attribute đã tồn tại trong hệ thống database',
                'integer' => ':attribute phải là số nguyên',
                'image' => ':attribute ảnh có dạng file ảnh',
                'mimes' => ':attribute ảnh có đuôi dạng jpeg,png,jpg,gif,svg',
                'file.required' => ':attribute không được để trống',
                'cat_id.required' => ':attribute không được để trống',
            ],
            //Thay đổi field thành nội dung tiếng việt
            [
                'name_product' => 'Tên sản phẩm',
                'price' => 'Giá',
                'price_old' => 'Giá cũ',
                'quantily' => 'Số lượng',
                'image' => 'Ảnh sản phẩm',
                'image_desc.*' => 'Ảnh mô tả sản phẩm',
                'brand_id' => 'Thương hiệu',
                'color_id' => 'Màu sắc',
                'cat_id' => 'Danh mục sản phẩm',
                'detail' => 'Chi tiết sản phẩm',
                'description' => 'Mô tả sản phẩm',
                'status' => 'Trạng thái'
            ]
        );

        //Lấy tất cả dữ liệu được nhập ra
        // return $request->all();

        //Nếu dữ liệu gửi lên có file(đường dẫn của file) thì lưu lại file đó vào một biến riêng
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            //Lấy tên file vd upload.jpg
            $filename = $file->getClientOriginalName();
            //Nếu nếu đường dẫn file không tồn tại thì
            if (!file_exists('public/images/products/' . $filename)) {
                //Di chuyển file vào đường dẫn chỉ định, lấy tên file
                $path = $file->move('public/images/products', $file->getClientOriginalName());
                //Lưu vào một biến đường dẫn đã di chuyển kèm tên file
                $image = 'images/products/' . $filename;
            //Nếu đường dẫn file tồn tại thì tạo tên file được thêm = thời gian + tên file
            } else {
                $newfilename = time() . '-' . $filename;
                $path = $file->move('public/images/products', $newfilename); //Di chuyển vào đường dẫn file cần lưu và lưu lại đường dẫn đó gửi lên server
                $image = 'images/products/' . $newfilename; //Đường dẫn của file lưu vào database
            }
            //Tạo trường thumbnail và thêm đường dẫn file vào
            $input['image'] = $image;
        }

        //Tên slug bằng dữ liệu nhập tên danh mục chuyển thành chuỗi ko dấu ngăn cách bởi dấu chấm phẩy
        $slug_name_product = $request->input('name_product');
        $slug = Str::slug($slug_name_product);
        $input['slug'] = $slug;

        //Từ model products tạo dữ liệu lưu vào database
        $product = Product::create([
            'name_product' => $request->input('name_product'),
            'slug' => $input['slug'],
            'price' => $request->input('price'),
            'price_old' => $request->input('price_old'),
            'quantily' => $request->input('quantily'),
            'image' => $input['image'],
            'brand_id' => $request->input('brand_id'),
            'color_id' => $request->input('color_id'),
            'cat_id' => $request->input('cat_id'),
            'product_featured' => $request->input('product_featured'),
            'product_selling' => $request->input('product_selling'),
            'detail' => $request->input('detail'),
            'description' => $request->input('description'),
            'status' => $request->input('status'),
            'user_id' => Auth::id(),
        ]);

        //Từ sản phầm thêm suy ra id để lưu vào database ảnh phụ
        $product_id = $product->id;

        //Upload ảnh mô tả sản phẩm kiểm tra ảnh mô tả có chưa
        if ($request->hasFile('image_desc')) {
            //Nếu có thì lưu vào 1 biến
            $files = $request->file('image_desc');
            //Duyệt các giá trị của file đó
            foreach ($files as $value) {
                $filenames = $value->getClientOriginalName();
                $typefiles = $value->getClientOriginalExtension();
                $names = pathinfo($filenames, PATHINFO_FILENAME);
                $paths = "images/product_details/" . $filenames;
                if (file_exists('public/'.$paths)) {
                    $filenames = $names . "-Copy." . $typefiles;
                    $paths = "images/product_details/" . $filenames;
                    $t = 1;
                    while (file_exists('public/'.$paths)) {
                        $copys = "-Copy({$t}).";
                        $paths = "images/product_details/" . $names . $copys . $typefiles;
                        $filenames = $names . $copys . $typefiles;
                        $t++;
                    }
                }
                $value->move('public/images/product_details/',  $filenames);
                // Lấy id của sản phẩm vừa thêm và thêm vào bảng ảnh phụ id sản phầm vừa thêm và ảnh phụ nhiều ảnh
                ProductImage::create([
                    'product_id' => $product_id,
                    'image_desc' => $paths
                ]);
            }
        }
        //Đưa sản phẩm vào danh sách vô hiệu hóa nếu trạng thái chờ duyệt
        if ($request->input('status') == "Chờ duyệt") {
            ProductImage::where('product_id', $product_id)->delete();
            Product::find($product_id)->delete();
        }
        //Ngược lại thì thông báo và chuyển hướng
        return redirect('admin/products/list')->with('status', 'Bạn đã thêm sản phẩm mới thành công');
    }

    //Phương thức hiển thị danh sách sản phẩm
    function list(Request $request)
    {
        //Lấy giá trị trạng thái ở url
        $status = $request->input('status');
        $list_act = [
            'delete' => 'Vô hiệu hóa',
        ];
        //Nếu url status có trạng thái là thùng rác thì xuất ra danh sách được khôi phục và xóa vĩnh viễn
        if ($status == 'trash') {
            $list_act = [
                'restore' => 'Kích hoạt lại',
                'forceDelete' => 'Xóa vĩnh viễn'
            ];
            //Thì lấy những bảng ghi đã xóa tạm thời và phân bài viết hiển thị và ngược lại thì hiển thị theo những trạng thái đã kích hoạt
            $list_products = Product::onlyTrashed()
                ->join('users', 'users.id', '=', 'products.user_id')
                ->join('product_cats', 'product_cats.id', '=', 'products.cat_id')
                ->select('products.*', 'users.name', 'product_cats.cat_name')
                ->paginate(6);
            $list_products ->withPath('?status=trash');
            //Ngược lại nếu ko có trạng thái thì tìm kiếm theo keyword nếu keyword null thì phân bài viết như cũ
        } else {
            $keyword = "";
            if ($request->input('keyword')) {
                $keyword = $request->input('keyword');
            }
            $list_products = Product::withoutTrashed()
                ->join('users', 'users.id', '=', 'products.user_id')
                ->join('product_cats', 'product_cats.id', '=', 'products.cat_id')
                ->select('products.*', 'users.name', 'product_cats.cat_name')
                ->where('name_product', 'LIKE', "%{$keyword}%")
                ->paginate(6);
        }
        //Đếm số bảng ghi bên ngoài thùng rác
        $count_product_active = Product::withoutTrashed()->count();
        //Đếm số bảng ghi trong thùng rác
        $count_product_trash = Product::onlyTrashed()->count();
        //Đếm tổng số bảng ghi đang hoạt động và trong thùng rác
        $count = [$count_product_active, $count_product_trash];
        //Trả lại cho view với những dữ liệu trên để hiển thị
        return view('admin.products.list', compact('list_act', 'list_products', 'count'));
    }

    //Phương thức cập nhập sản phẩm
    function update(Request $request, $id)
    {
        //Quy tắt yêu cầu dữ liệu
        //Lưu ý: Nếu để các quy tắc cách nhau bởi dấy phẩy theo quy tắc đăng ký user của laravel thì quy tắc sau ko hoạt động, phải để theo quy tắc cách nhau |
        $request->validate(
            [
                'name_product' => 'required|string|min:8|max:255|unique:products',
                'price' => 'integer|required',
                'price_old' => 'integer',
                'quantily' => 'integer|required',
                'image' => 'required|image|mimes:jpeg,png,jpg',
                'image_desc.*' => 'required|image|mimes:jpeg,png,jpg',
                'brand_id' => 'required',
                'color_id' => 'required',
                'cat_id' => 'required',
                'detail' => 'required',
                'description' => 'required',
                'status' => 'required',
            ],
            //Cấu hình thông báo lỗi
            [
                'required' => ':attribute không được để trống',
                'string' => ':attribute phải có dạng chuỗi',
                'max' => ':attribute có độ dài tối đa :max ký tự',
                'unique' => ':attribute đã tồn tại trong hệ thống database',
                'integer' => ':attribute phải là số nguyên',
                'image' => ':attribute ảnh có dạng file ảnh',
                'mimes' => ':attribute ảnh có đuôi dạng jpeg,png,jpg,gif,svg',
                'file.required' => ':attribute không được để trống',
                'cat_id.required' => ':attribute không được để trống',
            ],
            //Thay đổi field thành nội dung tiếng việt
            [
                'name_product' => 'Tên sản phẩm',
                'price' => 'Giá',
                'price_old' => 'Giá cũ',
                'quantily' => 'Số lượng',
                'image' => 'Ảnh sản phẩm',
                'image_desc.*' => 'Ảnh mô tả sản phẩm',
                'brand_id' => 'Thương hiệu',
                'color_id' => 'Màu sắc',
                'cat_id' => 'Danh mục sản phẩm',
                'detail' => 'Chi tiết sản phẩm',
                'description' => 'Mô tả sản phẩm',
                'status' => 'Trạng thái'
            ]
        );

        //Lấy tất cả dữ liệu được nhập ra
        //return $request->all();

        //Nếu dữ liệu gửi lên có file(đường dẫn của file) thì lưu lại file đó vào một biến riêng
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            //Lấy tên file vd upload.jpg
            $filename = $file->getClientOriginalName();
            //Nếu nếu đường dẫn file không tồn tại thì
            if (!file_exists('public/images/products/' . $filename)) {
                //Di chuyển file vào đường dẫn chỉ định, lấy tên file
                $path = $file->move('public/images/products', $file->getClientOriginalName());
                //Lưu vào một biến đường dẫn đã di chuyển kèm tên file
                $image = 'images/products/' . $filename;
            //Nếu đường dẫn file tồn tại thì tạo tên file được thêm = thời gian + tên file
            } else {
                $newfilename = time() . '-' . $filename;
                $path = $file->move('public/images/products', $newfilename); //Di chuyển vào đường dẫn file cần lưu và lưu lại đường dẫn đó gửi lên server
                $image = 'images/products/' . $newfilename; //Đường dẫn của file lưu vào database
            }
            //Tạo trường thumbnail và thêm đường dẫn file vào
            $input['image'] = $image;
        } else {
            $image = Product::find($id)->image;
        }

        //Tên slug bằng dữ liệu nhập tên danh mục chuyển thành chuỗi ko dấu ngăn cách bởi dấu chấm phẩy
        $slug_name_product = $request->input('name_product');
        $slug = Str::slug($slug_name_product);
        $input['slug'] = $slug;

        //Từ model products tạo dữ liệu lưu vào database
        $product = Product::where('id', $id)->update([
            'name_product' => $request->input('name_product'),
            'slug' => $input['slug'],
            'price' => $request->input('price'),
            'price_old' => $request->input('price_old'),
            'quantily' => $request->input('quantily'),
            'image' => $input['image'],
            'brand_id' => $request->input('brand_id'),
            'color_id' => $request->input('color_id'),
            'cat_id' => $request->input('cat_id'),
            'product_featured' => $request->input('product_featured'),
            'product_selling' => $request->input('product_selling'),
            'detail' => $request->input('detail'),
            'description' => $request->input('description'),
            'status' => $request->input('status'),
            'user_id' => Auth::id(),
        ]);

        //Update ảnh mô tả sản phẩm
        $product_images = ProductImage::where('product_id', $id)->get();
        foreach ($product_images as $value) {
            $url[] = $value->images;
        }
        //Nếu dữ liệu gửi lên có file(đường dẫn của file) thì lưu lại file đó vào một biến riêng
        if ($request->hasFile('image_desc')) {
            //Xóa ảnh cũ theo id sản phẩm
            ProductImage::where('product_id', $id)->forceDelete();
            $file = $request->file('image_desc');
            foreach ($file as $value) {
                $filenames = $value->getClientOriginalName();
                $typefiles = $value->getClientOriginalExtension();
                $names = pathinfo($filenames, PATHINFO_FILENAME);
                $paths = "images/product_details/" . $filenames;
                if (file_exists('public/'.$paths)) {
                    $filenames = $names . "-Copy." . $typefiles;
                    $paths = "images/product_details/" . $filenames;
                    $t = 1;
                    while (file_exists('public/'.$paths)) {
                        $copys = "-Copy({$t}).";
                        $paths = "images/product_details/" . $names . $copys . $typefiles;
                        $filenames = $names . $copys . $typefiles;
                        $t++;
                    }
                }
                $value->move('public/images/product_details/',  $filenames);
                ProductImage::create([
                    'product_id' => $id,
                    'image_desc' => $paths
                ]);
            }
        } else {
            foreach ($url as $value) {
                ProductImage::where('product_id', $id)
                ->update(['image_desc' => $value]);
            }
        }
        //Ngược lại thì thông báo và chuyển hướng
        return redirect('admin/products/list')->with('status', 'Cập nhật sản phẩm thành công');
    }

    //Phương thức chỉnh sửa sản phẩm
    function edit(Request $request, $id)
    {
        //Tìm kiếm sản phẩm theo id
        $product_edit = Product::where('id', $id)
            ->get();
        //Xử lí thêm danh mục sản phẩm để chuyển qua view
        $list_cats = productCat::all();
        //Lấy ra tất cả danh mục hiện có từ table trang
        $cat = DB::table('products')
            ->get()
            ->unique('cat_id');
        //Lấy tất cả các thương hiệu hiện có từ db
        $list_brands = DB::table('product_brands')
            ->get();
        //Lấy tất cả màu sản phẩm có trạng thái đang hoạt động
        $list_colors = DB::table('product_colors')
            ->get();
        //Lấy tất cả ảnh sản phẩm theo id sản phẩm đó
        $list_images = ProductImage::where('product_id', $id)
            ->get();
        //Trả dữ liệu được lấy ra từ table của db về trang chỉnh sửa trang view
        return view('admin.products.edit', compact('product_edit', 'list_cats', 'cat', 'list_brands', 'list_colors', 'list_images'));
    }

    //Phương thức vô hiệu hóa sản phẩm
    function disable($id)
    {
        //Tìm bài viết theo id và xóa tạm
        Product::find($id)->delete();
        //Tìm kiếm ảnh phụ theo product_id
        ProductImage::where('product_id', $id)->delete();
        //Chuyển hướng khi xóa xong kèm thông báo
        return redirect('admin/products/list')->with('status', 'Vô hiệu hóa sản phẩm thành công');
    }

    //Phương thức khôi phục sản phẩm
    function restore($id)
    {
        //Lấy theo id bài viết trong thùng rác khôi phục lại
        Product::onlyTrashed()->where('id', $id)->restore();
        ProductImage::onlyTrashed()->where('product_id', $id)->restore();
        //Chuyển hướng khi xóa xong kèm thông báo
        return redirect('admin/products/list')->with('status', 'Kích hoạt lại sản phẩm cho thành công');
    }

    //Phương thức xóa sản phẩm vĩnh viễn
    function delete($id)
    {
        //Tìm kiếm bài viết trong thùng rác (đã bị vô hiệu hóa) xóa vinh viễn chuyển hướng kèm thông báo
        ProductImage::withTrashed()->where('product_id', $id)->forceDelete();
        Product::withTrashed()->find($id)->forceDelete();
        //Chuyển hướng khi xóa xong kèm thông báo
        return redirect('admin/products/list')->with('status', 'Xóa sản phẩm thành công');
    }

    //Phương thức thực hiện tác vụ check
    function action(Request $request)
    {
        //Lấy ra danh sách phần tử đã chọn
        $list_check = $request->input('list_check');
        //Kiểm tra xem mảng $list_check['?'=> '?'] có phần tử nào ko ?
        if (isset($list_check)) {
            //Kiểm tra nếu có phần tử trong mảng thì
            if (!empty($list_check)) {
                //Lấy danh sách đã chọn tác vụ ra
                $act = $request->input('act');
                //Nếu tác vụ này là xóa thì cho phép xóa
                if ($act == 'delete') {
                    //Xóa danh sách check theo điều kiện id đó đã xác thực và đang hoạt động
                    ProductImage::withoutTrashed()
                        ->whereIn('product_id', $list_check)
                        ->delete();
                    Product::withoutTrashed()
                        ->whereIn('id', $list_check)
                        ->delete();
                    //Thực hiện chuyển hướng về danh sách trang kèm trạng thái thành công
                    return redirect('admin/products/list')->with('status', 'Vô hiệu hóa các sản phẩm thành công');
                }
                //Nếu tác vụ này là khôi phục thì cho phép khôi phục
                if ($act == 'restore') {
                    ProductImage::onlyTrashed()
                        ->whereIn('product_id', $list_check)
                        ->restore();
                    Product::onlyTrashed()
                        ->whereIn('id', $list_check)
                        ->restore();
                    //Thực hiện chuyển hướng khi thành công chọn thao tác khôi phục
                    return redirect('admin/products/list')->with('status', 'Bạn đã khôi phục các sản phẩm thành công');
                }
                //Nếu tác vụ này là xóa vĩnh viễn thì cho phép xóa vĩnh viễn
                if ($act == 'forceDelete') {
                    //Tìm table products với điều kiện id đó có trong danh sách check và lấy ra
                    ProductImage::onlyTrashed()
                        ->whereIn('product_id', $list_check)
                        ->forceDelete();
                    Product::onlyTrashed()
                        ->whereIn('id', $list_check)
                        ->forceDelete();
                    //Thực hiện chuyển hướng khi xóa vĩnh viễn thành công
                    return redirect('admin/products/list')->with('status', 'Bạn đã xõa vĩnh viễn các sản phẩm thành công');
                }
            }
            //Nếu trong danh sách check trống (không có tác vụ) thì chuyển hướng ra dach sách trang hiện có và hiển thị thông báo
            return redirect('admin/products/list')->with('status', 'Bạn phải chọn hình thức vô hiệu hóa, xóa vĩnh viễn hoặc kích hoạt lại');
        } else {
            //Nếu trong danh sách check trống (không có tác vụ) thì chuyển hướng ra dach sách trang hiện có và hiển thị thông báo
            return redirect('admin/products/list')->with('status', 'Bạn cần chọn phần tử cần thực thi');
        }
    }
}

