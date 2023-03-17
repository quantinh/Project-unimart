<?php

use Illuminate\Support\Facades\Auth; //Khai báo thư viện xác thực tài khoản tránh lỗi
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
Lưu ý: đặt tên cho route khi có tham số là id để phân biệt cho dễ
*/

//Định tuyến trang welcome laravel
// Route::get('/', function () {
//     return view('welcome');
// });

//Định tuyến xác thực
Auth::routes();

//Định tuyến cho phép xác thực
Auth::routes(['verify' => true]);

//Định tuyến tải ảnh lên quy trình này làm ở phần quản lí file-managger phần cuối
Route::group(['prefix' => 'laravel-filemanager'], function () { \UniSharp\LaravelFilemanager\Lfm::routes(); });

//============= Users ============//

//Định tuyến trang chủ người dùng
Route::get('/', 'IndexController@index');

//Định tuyến về trang chủ xác thực
Route::get('home', 'HomeController@index')->name('home');

//Định tuyến đến form đăng kí tài khoản
Route::get('dang-ki', 'UserController@showFormRegister')->name('form.register');

//Định tuyến đăng kí tài khoản
Route::post('dang-ki','UserController@register')->name('user.register');

//Định tuyến đến form đăng nhập tài khoản
Route::get('dang-nhap', 'UserController@showFormLogin')->name('form.login');

//Định tuyến đăng nhập tài khoản thêm dữ liệu
Route::post('dang-nhap', 'UserController@login')->name('user.login');

//Định tuyến đến đăng xuất trang
Route::get('dang-xuat', 'UserController@logout')->name('user.logout');

//============= Pages ============//

//Định tuyến về trang giới thiệu
Route::get('trang-{slug}', 'PageController@index')->name('page');

//============= Posts ============//

//Định tuyến bài viết
Route::get('bai-viet', 'PostController@list');

//Định tuyến chi tiết bài viết
Route::get('bai-viet/{slug}', 'PostController@detail')->name('blog.detail');

//============= Products ============//

//Định tuyến tìm kiếm sản phẩm
Route::post('tim-kiem', 'SearchController@search');

//Định tuyến tới danh sách sản phẩm
Route::get('danh-sach-san-pham', 'ProductController@list');

//Định tuyến danh mục sản phẩm theo sản phẩm
Route::get('danh-muc/{slug}', 'ProductController@category')->name('product.category');

//Định tuyến chi tiết sản phẩm
Route::get('chi-tiet-san-pham/{slug}', 'ProductController@detail')->name('product.detail');

//============= Carts ============//

//Định tuyến tới danh sách giỏ hàng
Route::get('gio-hang', 'CartController@show');

//Định tuyến thêm sản phẩm vào giỏ hàng
Route::post('gio-hang/them-san-pham', 'CartController@add')->name('cart.add');

//Định tuyến cập nhập số lượng trong giỏ hàng
Route::post('gio-hang/cap-nhap', 'CartController@update')->name('cart.update');

//Định tuyến xóa sản phẩm trong giỏ hàng
Route::get('gio-hang/xoa-san-pham/{rowId}', 'CartController@delete')->name('cart.remove');

//Định tuyến xóa toàn bộ sản phẩm trong giỏ hàng
Route::get('gio-hang/xoa-toan-bo', 'CartController@deleteAll')->name('cart.destroy');

//Định tuyến khách hàng đặt hàng validate dữ liệu
Route::post('gio-hang/store', 'CartController@store')->name('cart.store');

//Định tuyến khách hàng đặt hàng
Route::get('gio-hang/dat-hang', 'CartController@order')->name('cart.order');

//Định tuyến khách hàng đặt hàng thành công
Route::get('gio-hang/dat-hang/thanh-cong', 'CartController@success')->name('cart.success');

//Định tuyến in đơn hàng cho khách
Route::get('gio-hang/in-don-hang/{checkout_code}', 'CartController@print_order')->name('cart.print');

//Định tuyến mua ngay sản phẩm ở giỏ hàng
Route::get('mua-ngay/{id}', 'CartController@buyNow')->name('cart.buyNow');

//Áp dụng middleware cho các route cần xác thực
Route::middleware('auth')->group(function () {

    //============= Admins ============//

    //Định tuyến về trang dashboard với middleware phải đăng nhập với đk vào
    Route::get('admin', 'DashboardController@show');

    //Định tuyến thêm thành viên mới
    Route::get('admin/users/add', 'AdminUserController@add');

    //Định tuyến danh sách thành viên
    Route::post('admin/users/store', 'AdminUserController@store');

    //Định tuyến về danh sách admin
    Route::get('admin/users/list', 'AdminUserController@list');

    //Định tuyến cập nhập thông tin thành viên
    Route::post('admin/users/update/{id}', 'AdminUserController@update')->name('user.update');

    //Định tuyến chỉnh sửa thông tin thành viên
    Route::get('admin/users/edit/{id}', 'AdminUserController@edit')->name('user.edit');

    //Định tuyến khôi phục sản phẩm
    Route::get('admin/users/restore/{id}', 'AdminUserController@restore')->name('user.restore');

    //Định tuyến xóa thành viên theo id
    Route::get('admin/users/delete/{id}', 'AdminUserController@delete')->name('user.delete');

    //Định tuyến xóa vĩnh viễn thành viên theo id
    Route::get('admin/users/permanentlyDelete/{id}', 'AdminUserController@permanentlyDelete')->name('user.permanentlyDelete');

    //Định tuyến khôi phục thành viên
    Route::get('admin/users/action', 'AdminUserController@action');

    //============= Roles ============//

    //Định tuyến thêm quyền mới
    Route::get('admin/roles/add', 'AdminRoleController@add');

    //Định tuyến thêm validate quyền
    Route::get('admin/roles/store', 'AdminRoleController@store');

    //Định tuyến danh sách quyền
    Route::get('admin/roles/list', 'AdminRoleController@list');

    //Định tuyến cập nhập thông tin quyền
    Route::post('admin/roles/update/{id}', 'AdminRoleController@update')->name('admin.role.update');

    //Định tuyến chỉnh sửa thông tin quyền
    Route::get('admin/roles/edit/{id}', 'AdminRoleController@edit')->name('admin.role.edit');

    //Định tuyến xóa quyền vĩnh viễn xóa từng quyền
    Route::get('admin/roles/delete/{id}', 'AdminRoleController@delete')->name('admin.role.delete');

    //============= Pages ============//

    //Định tuyến thêm trang mới
    Route::get('admin/pages/add', 'AdminPageController@add');

    //Định tuyến danh sách trang
    Route::post('admin/pages/store', 'AdminPageController@store');

    //Định tuyến về danh sách trang
    Route::get('admin/pages/list', 'AdminPageController@list');

    //Định tuyến cập nhập thông tin trang
    Route::post('admin/pages/update/{id}', 'AdminPageController@update')->name('admin.page.update');

    //Định tuyến chỉnh sửa thông tin trang
    Route::get('admin/pages/edit/{id}', 'AdminPageController@edit')->name('admin.page.edit');

    //Định tuyến khôi phục bài viết trang
    Route::get('admin/pages/change/{id}', 'AdminPageController@changeStatus')->name('admin.page.change');

    //Định tuyến xóa trang vĩnh viễn xóa từng trang
    Route::get('admin/pages/delete/{id}', 'AdminPageController@delete')->name('admin.page.delete');

    //============= CatPosts ============//

    //Định tuyến thêm danh mục bài viết
    Route::get('admin/posts/cat/add', 'AdminPostController@addCat');

    //Định tuyến thêm danh mục bài viết
    Route::post('admin/posts/cat/store', 'AdminPostController@storeCat');

    //Định tuyến danh sách danh mục bài viết
    Route::get('admin/posts/cat/list', 'AdminPostController@listCat');

    //Định tuyến cập nhập danh mục bài viết
    Route::post('admin/posts/cat/update/{id}', 'AdminPostController@updateCat')->name('admin.post.cat.update');

    //Định tuyến sửa danh mục bài viết
    Route::get('admin/posts/cat/edit/{id}', 'AdminPostController@editCat')->name('admin.post.cat.edit');

    //Định tuyến thay đổi trạng thái danh mục bài viết
    Route::get('admin/posts/cat/change/{id}', 'AdminPostController@changeStatus')->name('admin.post.cat.change');

    //Định tuyến xóa danh mục bài viết
    Route::get('admin/posts/cat/delete/{id}', 'AdminPostController@deleteCat')->name('admin.post.cat.delete');

    //============= Posts ============//

    //Định tuyến thêm bài viết mới
    Route::get('admin/posts/add', 'AdminPostController@add');

    //Định tuyến danh sách bài viết
    Route::post('admin/posts/store', 'AdminPostController@store');

    //Định tuyến danh sách bài viết
    Route::get('admin/posts/list', 'AdminPostController@list');

    //Định tuyến cập nhập thông tin bài viết
    Route::post('admin/posts/update/{id}', 'AdminPostController@update')->name('admin.post.update');

    //Định tuyến chỉnh sửa thông tin bài viết
    Route::get('admin/posts/edit/{id}', 'AdminPostController@edit')->name('admin.post.edit');

    //Định tuyến khôi phục bài viết
    Route::get('admin/posts/restore/{id}', 'AdminPostController@restore')->name('admin.post.restore');

    //Định tuyến thực hiện nhiều tác vụ tên bảng ghi kích hoạt, vô hiệu hóa, xóa vĩnh viễn
    Route::post('admin/posts/action', 'AdminPostController@action')->name('admin.post.action');

    //Định tuyến vô hiệu hóa bài viết (xóa tạm)
    Route::get('admin/posts/disable/{id}', 'AdminPostController@disable')->name('admin.post.disable');

    //Định tuyến xóa bài viết vĩnh viễn
    Route::get('admin/posts/delete/{id}', 'AdminPostController@delete')->name('admin.post.delete');

    //============= CatProducts ============//

    //Định tuyến thêm danh mục sản phẩm
    Route::get('admin/products/cat/add', 'AdminProductController@addCat');

    //Định tuyến thêm danh mục sản phẩm
    Route::post('admin/products/cat/store', 'AdminProductController@storeCat');

    //Định tuyến danh sách danh mục sản phẩm
    Route::get('admin/products/cat/list', 'AdminProductController@listCat');

    //Định tuyến cập nhập danh mục sản phẩm
    Route::post('admin/products/cat/update/{id}', 'AdminProductController@updateCat')->name('admin.product.cat.update');

    //Định tuyến sửa danh mục sản phẩm
    Route::get('admin/products/cat/edit/{id}', 'AdminProductController@editCat')->name('admin.product.cat.edit');

    //Định tuyến thay đổi trạng thái danh mục sản phẩm
    Route::get('admin/products/cat/change/{id}', 'AdminProductController@changeStatus')->name('admin.product.cat.change');

    //Định tuyến xóa danh mục sản phẩm
    Route::get('admin/products/cat/delete/{id}', 'AdminProductController@deleteCat')->name('admin.product.cat.delete');

    //============= CatBrands ============//

    //Định tuyến thêm danh mục hãng sản phẩm
    Route::get('admin/products/brand/add', 'AdminProductController@addBrand');

    //Định tuyến thêm danh mục hãng sản phẩm
    Route::post('admin/products/brand/store', 'AdminProductController@storeBrand');

    //Định tuyến cập nhập danh mục hãng sản phẩm
    Route::post('admin/products/brand/update/{id}', 'AdminProductController@updateBrand')->name('admin.product.brand.update');

    //Định tuyến sửa danh mục hãng sản phẩm
    Route::get('admin/products/brand/edit/{id}', 'AdminProductController@editBrand')->name('admin.product.brand.edit');

    //Định tuyến xóa danh mục hãng sản phẩm
    Route::get('admin/products/brand/delete/{id}', 'AdminProductController@deleteBrand')->name('admin.product.brand.delete');

    //============= CatColors ============//

    //Định tuyến thêm danh mục màu sản phẩm
    Route::get('admin/products/color/add', 'AdminProductController@addColor');

    //Định tuyến thêm danh mục màu sản phẩm
    Route::post('admin/products/color/store', 'AdminProductController@storeColor');

    //Định tuyến cập nhập danh mục màu sản phẩm
    Route::post('admin/products/color/update/{id}', 'AdminProductController@updateColor')->name('admin.product.color.update');

    //Định tuyến sửa danh mục màu sản phẩm
    Route::get('admin/products/color/edit/{id}', 'AdminProductController@editColor')->name('admin.product.color.edit');

    //Định tuyến xóa danh mục màu sản phẩm
    Route::get('admin/products/color/delete/{id}', 'AdminProductController@deleteColor')->name('admin.product.color.delete');

    //============= Products ============//

    //Định tuyến thêm sản phẩm
    Route::get('admin/products/add', 'AdminProductController@add');

    //Định tuyến danh sách sản phẩm
    Route::post('admin/products/store', 'AdminProductController@store');

    //Định tuyến danh sách sản phẩm
    Route::get('admin/products/list', 'AdminProductController@list');

    //Định tuyến cập nhập thông tin sản phẩm
    Route::post('admin/products/update/{id}', 'AdminProductController@update')->name('admin.product.update');

    //Định tuyến chỉnh sửa thông tin sản phẩm
    Route::get('admin/products/edit/{id}', 'AdminProductController@edit')->name('admin.product.edit');

    //Định tuyến khôi phục sản phẩm
    Route::get('admin/products/restore/{id}', 'AdminProductController@restore')->name('admin.product.restore');

    //Định tuyến vô hiệu hóa sản phẩm
    Route::get('admin/products/disable/{id}', 'AdminProductController@disable')->name('admin.product.disable');

    //Định tuyến thực hiện nhiều tác vụ tên bảng ghi kích hoạt, vô hiệu hóa, xóa vĩnh viễn
    Route::get('admin/products/action', 'AdminProductController@action')->name('admin.product.action');

    //Định tuyến xóa sản phẩm vĩnh viễn
    Route::get('admin/products/delete/{id}', 'AdminProductController@delete')->name('admin.product.delete');

    //============= Sliders ============//

    //Định tuyến thêm slider
    Route::get('admin/sliders/add', 'AdminSliderController@add');

    //Định tuyến thêm slider
    Route::post('admin/sliders/store', 'AdminSliderController@store');

    //Định tuyến danh sách slider
    Route::get('admin/sliders/list', 'AdminSliderController@list');

    //Định tuyến di chuyển vị trí hiển thị slider lên trên
    Route::get('admin/sliders/up/{id}', 'AdminSliderController@up')->name('admin.slider.up');

    //Định tuyến di chuyển vị trí hiển thị slider xuống dưới
    Route::get('admin/sliders/down/{id}', 'AdminSliderController@down')->name('admin.slider.down');

    //Định tuyến thay đổi trạng thái danh mục sản phẩm
    Route::get('admin/sliders/change/{id}', 'AdminSliderController@changeStatus')->name('admin.slider.change');

    //Định tuyến xóa danh mục sản phẩm
    Route::get('admin/sliders/delete/{id}', 'AdminSliderController@delete')->name('admin.slider.delete');

    //============= Banners ============//

    //Định tuyến thêm ảnh banner quảng cáo
    Route::get('admin/banners/add', 'AdminBannerController@add');

    //Định tuyến thêm ảnh banner quảng cáo
    Route::post('admin/banners/store', 'AdminBannerController@store');

    //Định tuyến danh sách banner quảng cáo
    Route::get('admin/banners/list', 'AdminBannerController@list');

    //Định tuyến di chuyển vị trí hiển thị banner lên trên
    Route::get('admin/banners/up/{id}', 'AdminBannerController@up')->name('admin.banner.up');

    //Định tuyến di chuyển vị trí hiển thị banner xuống dưới
    Route::get('admin/banners/down/{id}', 'AdminBannerController@down')->name('admin.banner.down');

    //Định tuyến thay đổi trạng thái banner
    Route::get('admin/banners/change/{id}', 'AdminBannerController@changeStatus')->name('admin.banner.change');

    //Định tuyến xóa ảnh banner
    Route::get('admin/banners/delete/{id}', 'AdminBannerController@delete')->name('admin.banner.delete');

    //============= Orders ============//

    //Định tuyến danh sách đơn hàng
    Route::get('admin/orders/list', 'AdminOrderController@list');

    //Định tuyến tác vụ bảng ghi kích hoạt, vô hiệu hóa, xóa vĩnh viễn
    Route::post('admin/orders/action', 'AdminOrderController@action')->name('admin.order.action');

    //Định tuyến chi tiết đơn hàng
    Route::get('admin/orders/detail/{id}', 'AdminOrderController@detail')->name('admin.order.detail');

    //Định tuyến cập nhập chỉnh sửa đơn hàng
    Route::post('admin/orders/update/{id}', 'AdminOrderController@update')->name('admin.order.update');

    //Định tuyến hủy đơn hàng
    Route::get('admin/orders/cancel/{id}', 'AdminOrderController@cancel')->name('admin.order.cancel');

    //Định tuyến xóa vĩnh viên đơn hàng
    Route::get('admin/orders/delete/{id}', 'AdminOrderController@delete')->name('admin.order.delete');

    //============= Customers ============//

    //Định tuyến danh sách khách hàng
    Route::get('admin/orders/customer/list', 'AdminOrderController@listCustomer');

    //Định tuyến khôi phục khách hàng đã bị vô hiệu hóa
    Route::get('admin/orders/customer/restore/{id}', 'AdminOrderController@restore')->name('admin.customer.restore');

    //Định tuyến xóa tạm khách hàng theo id
    Route::get('admin/orders/customer/disable/{id}', 'AdminOrderController@disable')->name('admin.customer.disable');

    //Định tuyến xóa vĩnh viễn khách hàng theo id
    Route::get('admin/orders/customer/delete/{id}', 'AdminOrderController@deleteCustomer')->name('admin.customer.delete');

    //Định tuyến thực hiện nhiều tác vụ tên bảng ghi kích hoạt, vô hiệu hóa, xóa vĩnh viễn
    Route::get('admin/orders/customer/action', 'AdminOrderController@actionCustomer')->name('admin.customer.action');

    //============= Settings ============//

    //Định tuyến thêm thiết lập
    Route::get('admin/settings/add', 'AdminSettingController@add');

    //Định tuyến hiển thị danh sách thiết lập
    Route::get('admin/settings/list', 'AdminSettingController@list');

    //Định tuyến thêm validate trước khi thiết lập
    Route::post('admin/settings/store', 'AdminSettingController@store')->name('admin.setting.store');

    //Định tuyến chỉnh sửa thông tin thiết lập
    Route::get('admin/settings/edit/{id}', 'AdminSettingController@edit')->name('admin.setting.edit');

    //Định tuyến cập nhập thông tin thiết lập
    Route::post('admin/settings/update/{id}', 'AdminSettingController@update')->name('admin.setting.update');

    //Định tuyến thay đổi trạng thái thiết lập
    Route::get('admin/settings/changeStatus/{id}', 'AdminSettingController@changeStatus')->name('admin.setting.changeStatus');

    //Định tuyến xóa vĩnh viễn thiết lập
    Route::get('admin/settings/delete/{id}', 'AdminSettingController@delete')->name('admin.setting.delete');

    //============= Menus ============//

    //Định tuyến thêm menu
    Route::get('admin/menus/add', 'AdminMenuController@add');

    //Định tuyến hiển thị danh sách menu
    Route::get('admin/menus/list', 'AdminMenuController@list');

    //Định tuyến thêm validate trước khi thêm menu
    Route::post('admin/menus/store', 'AdminMenuController@store')->name('admin.menu.store');

    //Định tuyến chỉnh sửa thông tin menu
    Route::get('admin/menus/edit/{id}', 'AdminMenuController@edit')->name('admin.menu.edit');

    //Định tuyến cập nhập thông tin menu
    Route::post('admin/menus/update/{id}', 'AdminMenuController@update')->name('admin.menu.update');

    //Định tuyến thay đổi trạng thái menu
    Route::get('admin/menus/changeStatus/{id}', 'AdminMenuController@changeStatus')->name('admin.menu.changeStatus');

    //Định tuyến xóa vĩnh viễn menu
    Route::get('admin/menus/delete/{id}', 'AdminMenuController@delete')->name('admin.menu.delete');

    //============= Videos ============//

    //Định tuyến thêm video
    Route::get('admin/videos/add', 'AdminVideoController@add');

    //Định tuyến hiển thị danh sách video
    Route::get('admin/videos/list', 'AdminVideoController@list');

    //Định tuyến thêm validate trước khi thêm video
    Route::post('admin/videos/store', 'AdminVideoController@store')->name('admin.video.store');

    //Định tuyến chỉnh sửa thông tin video
    Route::get('admin/videos/edit/{id}', 'AdminVideoController@edit')->name('admin.video.edit');

    //Định tuyến cập nhập thông tin video
    Route::post('admin/videos/update/{id}', 'AdminVideoController@update')->name('admin.video.update');

    //Định tuyến thay đổi trạng thái video
    Route::get('admin/videos/changeStatus/{id}', 'AdminVideoController@changeStatus')->name('admin.video.changeStatus');

    //Định tuyến xóa vĩnh viễn video
    Route::get('admin/videos/delete/{id}', 'AdminVideoController@delete')->name('admin.video.delete');
});

