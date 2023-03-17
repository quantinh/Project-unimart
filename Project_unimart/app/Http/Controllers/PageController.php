<?php

namespace App\Http\Controllers;

use App\Product;
use App\Banner;
use App\Menu;
use App\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    //Phương thức hiển thị bài viết theo id
    function index($slug)
    {
        //Lấy ra tên menu 1
        $list_name_menu_one = Menu::where('name_menu', 'Trang chủ')->first();
        //Lấy ra tên menu 2
        $list_name_menu_two = Menu::where('name_menu', 'Sản phẩm')->first();
        //Lấy ra tên menu 3
        $list_name_menu_tree = Menu::where('name_menu', 'Blog')->first();
        //Danh sách trang
        $list_menus = Page::all();
        //Lấy 1 banner có vị trí 2 sidebar
        $banners_position_sidebar_two = Banner::where('position', 2)->get();
        //Lấy 6 sản phẩm bán chạy
        $product_sellings = Product::where('product_selling', 'Sản phẩm bán chạy')->take(6)->get();
        //Tìm trang theo slug
        $page = Page::where('slug', $slug)->first();
        //Chuyển hướng về view page
        return view('pages.page', compact('list_name_menu_one', 'list_name_menu_two', 'list_name_menu_tree', 'list_menus', 'banners_position_sidebar_two', 'product_sellings', 'page'));
    }
}
