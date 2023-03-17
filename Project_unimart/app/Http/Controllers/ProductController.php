<?php

namespace App\Http\Controllers;
use App\Product;
use App\ProductCat;
use App\ProductImage;
use App\Menu;
use App\Banner;
use App\Page;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //Phương thức hiển thị danh sách sản phẩm
    function list(Request $request)
    {
        //Lấy ra tên menu 1
        $list_name_menu_one = Menu::where('name_menu', 'Trang chủ')->first();
        //Lấy ra tên menu 2
        $list_name_menu_two = Menu::where('name_menu', 'Sản phẩm')->first();
        //Lấy ra tên menu 3
        $list_name_menu_tree = Menu::where('name_menu', 'Blog')->first();
        //Danh sách trang menu header
        $list_menus = Page::all();
        //Lấy danh mục cha
        $categorys = ProductCat::where('parent_id', 0)->get();
        //Lấy 6 sản phẩm bán chạy sidebar-product
        $product_sellings = Product::where('product_selling', 'Sản phẩm bán chạy')->take(6)->get();
        //Lấy 1 banner có vị trí 1 sidebar-product
        $banners_position_sidebar_one = Banner::where('position', 1)->first();
        //Lấy 1 banner có vị trí 2 sidebar-product
        $banners_position_sidebar_two = Banner::where('position', 2)->first();
        //Danh sách các action sắp xếp lọc
        $list_action = [
            //Sắp xếp sản phẩm từ a-z là sản phẩm từ cũ tới mới (theo ngày tháng)
            'asc' => 'Sản phẩm cũ hơn',
            //Sắp xếp sản phẩm từ z-a là sản phẩm từ mới tới cũ (theo ngày tháng)
            'desc' => 'Sản phẩm mới nhất',
            //Sắp xếp sản phẩm giá theo thứ tự a-z
            'price-asc' => 'Giá thấp lên cao',
            //Sắp xếp sản phẩm giá theo thứ tự z-a
            'price-desc' => 'Giá cao xuống thấp'
        ];
        //Nếu dữ liệu chọn gửi lên có name = 'act'  value = 'các giá trị bên dưới' thì lấy dữ liệu theo bên dưới
        $status_product = $request->input('act');
        if($status_product == "asc") {
            //Lấy 12 sản phẩm theo danh mục pc-workstation lấy ra 8 sản phẩm
            $product_by_categorys = Product::join('product_cats', 'product_cats.id', '=', 'products.cat_id')
                ->select('products.*', 'product_cats.cat_name')
                ->where('cat_name', 'PC - WORKSTATION')
                ->take(4)
                ->orderBy('created_at', 'asc')
                ->get();
            //Lấy 8 sản phẩm theo danh mục cpu bộ vi xử lí
            $product_by_categorys_sub = Product::join('product_cats', 'product_cats.id', '=', 'products.cat_id')
                ->select('products.*', 'product_cats.cat_name')
                ->where('cat_name', 'CPU - BỘ VI XỬ LÝ')
                ->take(4)
                ->orderBy('created_at', 'asc')
                ->get();
            //Lấy 8 sản phẩm theo danh mục vga card màn hình
            $product_by_categorys_sub_card = Product::join('product_cats', 'product_cats.id', '=', 'products.cat_id')
                ->select('products.*', 'product_cats.cat_name')
                ->where('cat_name', 'VGA - CARD MÀN HÌNH')
                ->take(4)
                ->orderBy('created_at', 'asc')
                ->get();
            //Lấy 8 sản phẩm theo danh mục màn hình máy tính
            $product_by_categorys_sub_screen = Product::join('product_cats', 'product_cats.id', '=', 'products.cat_id')
                ->select('products.*', 'product_cats.cat_name')
                ->where('cat_name', 'MÀN HÌNH MÁY TÍNH')
                ->take(4)
                ->orderBy('created_at', 'asc')
                ->get();
            //Lấy 8 sản phẩm theo danh mục bàn phím gaming
            $product_by_categorys_sub_keyboard = Product::join('product_cats', 'product_cats.id', '=', 'products.cat_id')
                ->select('products.*', 'product_cats.cat_name')
                ->where('cat_name', 'BÀN PHÍM GAME')
                ->take(4)
                ->orderBy('created_at', 'desc')
                ->get();
            $count_total_product_current = count($product_by_categorys) + count($product_by_categorys_sub) + count($product_by_categorys_sub_card) + count($product_by_categorys_sub_screen) + count($product_by_categorys_sub_keyboard);
        } else if($status_product == "price-asc") {
            //Lấy 12 sản phẩm theo danh mục pc-workstation lấy ra 8 sản phẩm
            $product_by_categorys = Product::join('product_cats', 'product_cats.id', '=', 'products.cat_id')
                ->select('products.*', 'product_cats.cat_name')
                ->where('cat_name', 'PC - WORKSTATION')
                ->take(4)
                ->orderBy('price', 'asc')
                ->get();
            //Lấy 8 sản phẩm theo danh mục cpu bộ vi xử lí
            $product_by_categorys_sub = Product::join('product_cats', 'product_cats.id', '=', 'products.cat_id')
                ->select('products.*', 'product_cats.cat_name')
                ->where('cat_name', 'CPU - BỘ VI XỬ LÝ')
                ->take(4)
                ->orderBy('price', 'asc')
                ->get();
            //Lấy 8 sản phẩm theo danh mục vga card màn hình
            $product_by_categorys_sub_card = Product::join('product_cats', 'product_cats.id', '=', 'products.cat_id')
                ->select('products.*', 'product_cats.cat_name')
                ->where('cat_name', 'VGA - CARD MÀN HÌNH')
                ->take(4)
                ->orderBy('price', 'asc')
                ->get();
            //Lấy 8 sản phẩm theo danh mục màn hình máy tính
            $product_by_categorys_sub_screen = Product::join('product_cats', 'product_cats.id', '=', 'products.cat_id')
                ->select('products.*', 'product_cats.cat_name')
                ->where('cat_name', 'MÀN HÌNH MÁY TÍNH')
                ->take(4)
                ->orderBy('price', 'asc')
                ->get();
            //Lấy 8 sản phẩm theo danh mục bàn phím gaming
            $product_by_categorys_sub_keyboard = Product::join('product_cats', 'product_cats.id', '=', 'products.cat_id')
                ->select('products.*', 'product_cats.cat_name')
                ->where('cat_name', 'BÀN PHÍM GAME')
                ->take(4)
                ->orderBy('created_at', 'desc')
                ->get();
            $count_total_product_current = count($product_by_categorys) + count($product_by_categorys_sub) + count($product_by_categorys_sub_card) + count($product_by_categorys_sub_screen) + count($product_by_categorys_sub_keyboard);
        } else if($status_product == "price-desc") {
            //Lấy 12 sản phẩm theo danh mục pc-workstation lấy ra 8 sản phẩm
            $product_by_categorys = Product::join('product_cats', 'product_cats.id', '=', 'products.cat_id')
                ->select('products.*', 'product_cats.cat_name')
                ->where('cat_name', 'PC - WORKSTATION')
                ->take(4)
                ->orderBy('price', 'desc')
                ->get();
            //Lấy 8 sản phẩm theo danh mục cpu bộ vi xử lí
            $product_by_categorys_sub = Product::join('product_cats', 'product_cats.id', '=', 'products.cat_id')
                ->select('products.*', 'product_cats.cat_name')
                ->where('cat_name', 'CPU - BỘ VI XỬ LÝ')
                ->take(4)
                ->orderBy('price', 'desc')
                ->get();
            //Lấy 8 sản phẩm theo danh mục vga card màn hình
            $product_by_categorys_sub_card = Product::join('product_cats', 'product_cats.id', '=', 'products.cat_id')
                ->select('products.*', 'product_cats.cat_name')
                ->where('cat_name', 'VGA - CARD MÀN HÌNH')
                ->take(4)
                ->orderBy('price', 'desc')
                ->get();
            //Lấy 8 sản phẩm theo danh mục màn hình máy tính
            $product_by_categorys_sub_screen = Product::join('product_cats', 'product_cats.id', '=', 'products.cat_id')
                ->select('products.*', 'product_cats.cat_name')
                ->where('cat_name', 'MÀN HÌNH MÁY TÍNH')
                ->take(4)
                ->orderBy('price', 'desc')
                ->get();
            //Lấy 8 sản phẩm theo danh mục bàn phím gaming
            $product_by_categorys_sub_keyboard = Product::join('product_cats', 'product_cats.id', '=', 'products.cat_id')
                ->select('products.*', 'product_cats.cat_name')
                ->where('cat_name', 'BÀN PHÍM GAME')
                ->take(4)
                ->orderBy('created_at', 'desc')
                ->get();
            $count_total_product_current = count($product_by_categorys) + count($product_by_categorys_sub) + count($product_by_categorys_sub_card) + count($product_by_categorys_sub_screen) + count($product_by_categorys_sub_keyboard);
        } else {
            //Lấy 12 sản phẩm theo danh mục pc-workstation lấy ra 8 sản phẩm
            $product_by_categorys = Product::join('product_cats', 'product_cats.id', '=', 'products.cat_id')
                ->select('products.*', 'product_cats.cat_name')
                ->where('cat_name', 'PC - WORKSTATION')
                ->take(4)
                ->orderBy('created_at', 'desc')
                ->get();
            //Lấy 8 sản phẩm theo danh mục cpu bộ vi xử lí
            $product_by_categorys_sub = Product::join('product_cats', 'product_cats.id', '=', 'products.cat_id')
                ->select('products.*', 'product_cats.cat_name')
                ->where('cat_name', 'CPU - BỘ VI XỬ LÝ')
                ->take(4)
                ->orderBy('created_at', 'desc')
                ->get();
            //Lấy 8 sản phẩm theo danh mục vga card màn hình
            $product_by_categorys_sub_card = Product::join('product_cats', 'product_cats.id', '=', 'products.cat_id')
                ->select('products.*', 'product_cats.cat_name')
                ->where('cat_name', 'VGA - CARD MÀN HÌNH')
                ->take(4)
                ->orderBy('created_at', 'desc')
                ->get();
            //Lấy 8 sản phẩm theo danh mục màn hình máy tính
            $product_by_categorys_sub_screen = Product::join('product_cats', 'product_cats.id', '=', 'products.cat_id')
                ->select('products.*', 'product_cats.cat_name')
                ->where('cat_name', 'MÀN HÌNH MÁY TÍNH')
                ->take(4)
                ->orderBy('created_at', 'desc')
                ->get();
            //Lấy 8 sản phẩm theo danh mục bàn phím gaming
            $product_by_categorys_sub_keyboard = Product::join('product_cats', 'product_cats.id', '=', 'products.cat_id')
                ->select('products.*', 'product_cats.cat_name')
                ->where('cat_name', 'BÀN PHÍM GAME')
                ->take(4)
                ->orderBy('created_at', 'desc')
                ->get();
            $count_total_product_current = count($product_by_categorys) + count($product_by_categorys_sub) + count($product_by_categorys_sub_card) + count($product_by_categorys_sub_screen) + count($product_by_categorys_sub_keyboard);
        }
        //Đếm tổng sản phẩm có của hệ thống
        $count_total_product = Product::count();
        //Chuyển dữ liệu cho view
        return view('products.list', compact(
            'list_name_menu_one',
            'list_name_menu_two',
            'list_name_menu_tree',
            'list_menus',
            'categorys',
            'product_sellings',
            'banners_position_sidebar_one',
            'banners_position_sidebar_two',
            'list_action',
            'count_total_product_current',
            'count_total_product',
            'product_by_categorys',
            'product_by_categorys_sub',
            'product_by_categorys_sub_card',
            'product_by_categorys_sub_screen',
            'product_by_categorys_sub_keyboard'
        ));
    }

    //Phương thức hiển thị chi tiết bài viết blog
    function detail($slug)
    {
        //Lấy ra tên menu 1
        $list_name_menu_one = Menu::where('name_menu', 'Trang chủ')->first();
        //Lấy ra tên menu 2
        $list_name_menu_two = Menu::where('name_menu', 'Sản phẩm')->first();
        //Lấy ra tên menu 3
        $list_name_menu_tree = Menu::where('name_menu', 'Blog')->first();
        //Danh sách trang menu
        $list_menus = Page::all();
        //Lấy 6 sản phẩm bán chạy sidebar-detail-post
        $product_sellings = Product::where('product_selling', 'Sản phẩm bán chạy')->take(6)->get();
        //Lấy 1 banner có vị trí 1 sidebar
        $banners_position_sidebar_one = Banner::where('position', 1)->first();
        //Lấy 1 banner có vị trí 2 sidebar
        $banners_position_sidebar_two = Banner::where('position', 2)->first();
        //Tìm kiếm sản phẩm theo slug khi click vào xem chi tiết
        $product = Product::where('slug', $slug)->first();
        //Lấy danh sách ảnh phụ của một từ slug=> id nào đó
        $list_images_info = ProductImage::where('product_id', $product->id)->get();
        //Join vào để lấy ra những sản phẩm có cùng danh mục với sản phẩm hiện tại nhưng không được trùng với hiện tại
        $list_product_same_cats = ProductCat::join('products', 'products.cat_id', '=', 'product_cats.id')
        ->select('products.*', 'product_cats.cat_name')
        ->where([
            ['products.cat_id', $product->cat_id],
            ['products.id', '<>', $product->id]
        ])
        ->orderby('products.price', 'desc')
        ->get();
        //Chuyển hướng
        return view('products.detail', compact('list_name_menu_one', 'list_name_menu_two', 'list_name_menu_tree', 'list_menus', 'product_sellings', 'banners_position_sidebar_one', 'banners_position_sidebar_two', 'product', 'list_images_info', 'list_product_same_cats'));
    }

    //Phương thức hiển thị sản phẩm theo danh mục
    function category(Request $request, $slug)
    {
        //Lấy ra tên menu 1
        $list_name_menu_one = Menu::where('name_menu', 'Trang chủ')->first();
        //Lấy ra tên menu 2
        $list_name_menu_two = Menu::where('name_menu', 'Sản phẩm')->first();
        //Lấy ra tên menu 3
        $list_name_menu_tree = Menu::where('name_menu', 'Blog')->first();
        //Danh sách trang menu header
        $list_menus = Page::all();
        //Lấy danh mục cha phần sidebar-product
        $categorys = ProductCat::where('parent_id', 0)->get();
        //Lấy 6 sản phẩm bán chạy sidebar-product
        $product_sellings = Product::where('product_selling', 'Sản phẩm bán chạy')->take(6)->get();
        //Lấy 1 banner có vị trí 1 sidebar-product
        $banners_position_sidebar_one = Banner::where('position', 1)->first();
        //Lấy 1 banner có vị trí 2 sidebar-product
        $banners_position_sidebar_two = Banner::where('position', 2)->first();
        //Tìm danh mục theo slug đã chọn
        $category = ProductCat::where('slug', $slug)->first();
        //Từ slug suy ra id của danh mục đó
        $cat_id = $category->id;
        //Từ danh mục suy ra tên danh mục đó
        $cat_name = $category->cat_name;
        //Từ danh mục suy ra được slug để lấy theo đường dẫn cụ thể chỗ menu
        $cat_slug = $category->slug;
        //Sắp xếp sản phẩm theo giá tạo một mảng có chưa các phần tử giá, thứ tự sắp xếp tăng dần, lớn hơn, khác,
        $orderby = array(
            'products.price',
            'asc',
            '>',
            '!=',
            0 ,
            0
        );
        //Kiểm tra dữ liệu gửi lên nếu dữ liệu gửi lên có kiểu sắp xếp theo giá giảm dần thì truy cập vào phần tử thứ 1 cập nhập giá trị giảm dần
        if ($request->orderby) {
            if ($request->orderby == "price_desc") {
                $orderby[1] = "desc";
            //Nếu dữ liệu gửi lên có giá trị là tăng dần thì truy cập vào phần tử 0, 1 của mảng orderby cập nhập vào giá trị lấy tên sản phẩm và sắp xếp theo thứ tự tăng dần
            } elseif ($request->orderby == "asc") {
                $orderby[0] = "products.name_product";
                $orderby[1] = "asc";
            //Nếu dữ liệu gửi lên có giá trị là giảm dần thì truy cập vào phần tử 0, 1 của mảng orderby cập nhập vào giá trị lấy tên sản phẩm và sắp xếp theo thứ tự giảm dần
            } elseif ($request->orderby == "desc") {
                $orderby[0] = "products.name_product";
                $orderby[1] = "desc";
            //Nếu dữ liệu gửi lên có giá trị là 1 thì truy cập vào phần tử 2 của mảng thay đổi dấu bé và truy cập vào phần tử số 4 thay đổi số 5000000
            } elseif ($request->orderby == 1) {
                $orderby[2] = "<";
                $orderby[4] = 5000000;
            //Nếu dữ liệu gửi lên có giá trị là 2 thì truy cập vào phần tử 2 của mảng thay đổi dấu lớn hơn hoặc bằng và truy cập vào phần tử số 4 thay đổi số 5000000
            } elseif ($request->orderby == 2) {
                $orderby[2] = ">=";
                $orderby[4] = 5000000;
                $orderby[3] = "<";
                $orderby[5] = 10000000;
            //Nếu dữ liệu gửi lên có giá trị là 3 thì truy cập vào phần tử 2 của mảng thay đổi dấu lớn hơn hoặc bằng và truy cập vào phần tử số 4 thay đổi số 10000000 ...
            } elseif ($request->orderby == 3) {
                $orderby[2] = ">=";
                $orderby[4] = 10000000;
                $orderby[3] = "<";
                $orderby[5] = 20000000;
            //Nếu dữ liệu gửi lên có giá trị là 4 thì truy cập vào phần tử 2 của mảng thay đổi dấu lớn hơn hoặc bằng và truy cập vào phần tử số 4 thay đổi số 20000000 ...
            } elseif ($request->orderby == 4) {
                $orderby[2] = ">";
                $orderby[4] = 20000000;
            //Nếu ngược lại orderby không sắp xếp theo giá giảm dần thì cập nhập lại giá trị cho phần tử thứ nhất là tăng dần
            } else {
                $orderby[1] = "asc";
            }
        }
        //Kiểm tra danh mục cha có tồn tại thì lấy dữ liệu theo danh mục cha
        if ($category->parent_id == 0) {
            //Đếm tổng sản phẩm có tại cửa hàng
            $count_total_product = Product::count();
            $list_items = ProductCat::join('products', 'products.cat_id', '=', 'product_cats.id')
                ->select('products.*')
                ->where([
                    ['products.cat_id', $cat_id],
                    ['products.price', $orderby[2], $orderby[4]],
                    ['products.price', $orderby[3], $orderby[5]]
                ])
                ->orderby($orderby[0], $orderby[1])
                ->paginate(20);
            return view('products.cat-product', compact(
                'list_name_menu_one',
                'list_name_menu_two',
                'list_name_menu_tree',
                'list_menus',
                'categorys',
                'count_total_product',
                'list_items',
                'cat_name',
                'cat_slug',
                'product_sellings',
                'banners_position_sidebar_one',
                'banners_position_sidebar_two',
                )
            );
        //Ngược lại nếu tồn tại danh mục con thì lấy theo danh mục con
        } else {
            //Đếm tổng sản phẩm có tại cửa hàng
            $count_total_product = Product::count();
            $list_items = ProductCat::join('products', 'products.cat_id', '=', 'product_cats.id')
                ->select('products.*')
                ->where([
                    ['products.cat_id', $cat_id],
                    ['products.price', $orderby[2], $orderby[4]],
                    ['products.price', $orderby[3], $orderby[5]]
                ])
                ->orderby($orderby[0], $orderby[1])
                ->paginate(20);
            return view('products.cat-product', compact(
                    'list_name_menu_one',
                    'list_name_menu_two',
                    'list_name_menu_tree',
                    'list_menus',
                    'categorys',
                    'count_total_product',
                    'list_items',
                    'cat_name',
                    'cat_slug',
                    'product_sellings',
                    'banners_position_sidebar_one',
                    'banners_position_sidebar_two',
                )
            );
        }
    }
}
