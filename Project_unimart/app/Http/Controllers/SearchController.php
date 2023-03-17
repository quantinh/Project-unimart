<?php

namespace App\Http\Controllers;

use App\Page;
use App\Product;
use App\ProductCat;
use App\Banner;
use App\Menu;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    //Phương thức tìm kiếm sản phẩm
    function search(Request $request) {
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
        //Lấy 6 sản phẩm bán chạy
        $product_sellings = Product::where('product_selling', 'Sản phẩm bán chạy')->take(6)->get();
        //Lấy 1 banner có vị trí 1 sidebar-product
        $banners_position_sidebar_one = Banner::where('position', 1)->first();
        //Lấy 1 banner có vị trí 2 sidebar-product
        $banners_position_sidebar_two = Banner::where('position', 2)->first();
        //Kiểm tra dữ liệu tìm kiếm có key ko nếu có
        if($request->keyword) {
            //Lấy danh sách sản phẩm tìm kiếm hiển thị theo tên sản phẩm theo danh mục
            $list_items = ProductCat::join('products','products.cat_id','=','product_cats.id')
                ->select('products.*')
                ->where('products.name_product', 'LIKE', "%{$request->keyword}%")
                ->orwhere('product_cats.cat_name', 'LIKE', "%{$request->keyword}%")
                ->get();
            $keyword = $request->keyword;
            return view('products.search',compact('list_name_menu_one', 'list_name_menu_two', 'list_name_menu_tree', 'list_menus', 'categorys', 'product_sellings', 'banners_position_sidebar_one', 'banners_position_sidebar_two', 'list_items','keyword'));
        } else {
            return redirect()->back();
        }
    }
}
