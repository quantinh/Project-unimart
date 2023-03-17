<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Slider;
use App\Banner;
use App\Video;
use App\ProductCat;
use App\Product;
use App\Page;
use App\Post;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    //Phương thức hiển thị dữ liệu trang home users
    function index()
    {
        //Lấy ra tên menu 1
        $list_name_menu_one = Menu::where('name_menu', 'Trang chủ')->first();
        //Lấy ra tên menu 2
        $list_name_menu_two = Menu::where('name_menu', 'Sản phẩm')->first();
        //Lấy ra tên menu 3
        $list_name_menu_tree = Menu::where('name_menu', 'Blog')->first();
        //Lấy danh sách trang giới thiệu, liên hệ
        $list_menus = Page::all();
        //Lấy tất cả danh sách slider xuống và sắp xếp theo thứ tự tăng dần lấy 6 slider
        $list_sliders = Slider::orderby('position', 'asc')->take(6)->get();
        //Lấy 1 banner có vị trí ở phần đầu trang first thì lấy ra không cần foreach
        $banners_position_head = Banner::where('position', 3)->first();
        //Lấy 1 banner có vị trí 1 sidebar
        $banners_position_sidebar_one = Banner::where('position', 1)->first();
        //Lấy 1 banner có vị trí 2 sidebar
        $banners_position_sidebar_two = Banner::where('position', 2)->first();
        //Lấy 1 banner có vị trí 3 sidebar
        $banners_position_sidebar_tree = Banner::where('position', 4)->first();
        //Lấy 1 banner có vị trí 4 sidebar
        $banners_position_sidebar_five = Banner::where('position', 5)->first();
        //Lấy 1 video có id = 1 video đầu tiên
        $link_video = Video::where('id', 1)->first();
        //Lấy danh mục cha
        $categorys = ProductCat::where('parent_id', 0)->get();
        //Lấy 6 sản phẩm nổi bật
        $product_featureds = Product::where('product_featured', 'Sản phẩm nối bật')->take(6)->get();
        //Lấy 6 sản phẩm bán chạy
        $product_sellings = Product::where('product_selling', 'Sản phẩm bán chạy')->take(4)->get();
        //Lấy 12 sản phẩm theo danh mục pc-workstation lấy ra 12 sản phẩm
        $product_by_categorys = Product::join('product_cats', 'product_cats.id', '=', 'products.cat_id')
            ->select('products.*', 'product_cats.cat_name')
            ->where('cat_name', 'PC - WORKSTATION')
            ->take(12)
            ->get();
        //Lấy 4 sản phẩm theo danh mục cpu bộ vi xử lí
        $product_by_categorys_sub = Product::join('product_cats', 'product_cats.id', '=', 'products.cat_id')
            ->select('products.*', 'product_cats.cat_name')
            ->where('cat_name', 'CPU - BỘ VI XỬ LÝ')
            ->take(4)
            ->get();
        //Lấy 4 sản phẩm theo danh mục vga card màn hình
        $product_by_categorys_sub_card = Product::join('product_cats', 'product_cats.id', '=', 'products.cat_id')
            ->select('products.*', 'product_cats.cat_name')
            ->where('cat_name', 'VGA - CARD MÀN HÌNH')
            ->take(4)
            ->get();
        //Lấy 4 sản phẩm theo danh mục màn hình máy tính
        $product_by_categorys_sub_screen = Product::join('product_cats', 'product_cats.id', '=', 'products.cat_id')
            ->select('products.*', 'product_cats.cat_name')
            ->where('cat_name', 'MÀN HÌNH MÁY TÍNH')
            ->take(4)
            ->get();
        // return $product_by_categorys_sub_card;
        return view('home.home', compact(
                'list_name_menu_one',
                'list_name_menu_two',
                'list_name_menu_tree',
                'list_menus',
                'list_sliders',
                'banners_position_head',
                'banners_position_sidebar_one',
                'banners_position_sidebar_two',
                'banners_position_sidebar_tree',
                'banners_position_sidebar_five',
                'link_video',
                'categorys',
                'product_featureds',
                'product_sellings',
                'product_by_categorys',
                'product_by_categorys_sub',
                'product_by_categorys_sub_card',
                'product_by_categorys_sub_screen'
            )
        );
    }
}


