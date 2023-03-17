<?php

namespace App\Http\Controllers;

use App\Product;
use App\Banner;
use App\Menu;
use App\Page;
use App\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    //Phương thức hiển thị danh sách bài viết blog
    function list()
    {
        //Lấy ra tên menu 1
        $list_name_menu_one = Menu::where('name_menu', 'Trang chủ')->first();
        //Lấy ra tên menu 2
        $list_name_menu_two = Menu::where('name_menu', 'Sản phẩm')->first();
        //Lấy ra tên menu 3
        $list_name_menu_tree = Menu::where('name_menu', 'Blog')->first();
        //Danh sách trang menu
        $list_menus = Page::all();
        //Lấy 6 sản phẩm bán chạy sidebar-post
        $product_sellings = Product::where('product_selling', 'Sản phẩm bán chạy')->take(6)->get();
        //Lấy 1 banner có vị trí 1 sidebar-post
        $banners_position_sidebar_one = Banner::where('position', 1)->first();
        //Danh sách bài viết theo danh mục lấy ra tên người tạo bài viết lấy ra bài viết thuộc danh mục nào
        $list_posts = Post::join('post_cats', 'post_cats.id', '=', 'posts.cat_id')
            ->join('users', 'users.id', '=', 'posts.user_id')
            ->select('posts.*', 'users.name', 'post_cats.cat_name')
            ->paginate(6);
        return view('posts.list', compact('list_name_menu_one', 'list_name_menu_two', 'list_name_menu_tree','list_menus', 'product_sellings', 'banners_position_sidebar_one', 'list_posts'));
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
        //Tìm kiếm bài viết theo slug ko cần foreach load trang lâu
        $post = Post::where('slug', $slug)->first();
        return view('posts.detail', compact('list_name_menu_one', 'list_name_menu_two', 'list_name_menu_tree', 'list_menus', 'product_sellings', 'post'));
    }
}
