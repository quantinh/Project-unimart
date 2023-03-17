<?php

namespace App\Http\Controllers;

use App\Page;
use App\Menu;
use App\Product;
use App\Order;
use App\DetailOrder;
use App\Customer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Mail\SendInfoOrder;
use App\Mail\NewOrder;
use Illuminate\Support\Facades\Mail;
use PDF;

class CartController extends Controller
{
    //Phương thức thêm sản phẩm vào giỏ hàng
    function add(Request $request) {
        $product = Product::find($request->input('id'));
        Cart::add([
            'id' => $product->id,
            'name' => $product->name_product,
            'qty' => 1,
            'price' => $product->price,
            'options' => ['image' => $product->image, 'warehouse' => $product->quantily, 'slug' => $product->slug]
        ]);

        //Lấy id session đã lưu
        $customer_id = session()->get('id');
        $list_cart = "<h4 class='header-cart-heading'>Sản phẩm đã thêm</h4>";
        $list_cart . "<ul class='header-cart-list-item'>";
            foreach(Cart::content() as $item) {
                $list_cart = $list_cart .
                    "<li class='header-cart-item'>
                        <a href='" . route('product.detail', $item->options->slug) . "' class='header-cart-item-link'>
                            <img src='" . asset($item->options->image) . "' class='header-cart-img'>
                        </a>
                        <div class='header-cart-item-info'>
                            <div class='header-cart-item-head'>
                                <a href='" . route('product.detail', $item->options->slug) . "' class='header-cart-item-link'>
                                    <h5 class='header-cart-item-name'>". Str::of($item->name)->limit(45) ."</h5>
                                </a>
                                <div class='header-cart-item-price-wrap'>
                                    <span class='header-cart-item-price'>". number_format($item->price, 0, ',', '.') ."đ</span>
                                    <span class='header-cart-item-multiply'>x</span>
                                    <span class='header-cart-item-qnt'>". $item->qty ."</span>
                                </div>
                            </div>
                            <div class='header-cart-item-body'>
                                <span class='header-cart-item-description'></span>
                                <a class='del-cart' href='". route('cart.remove', $item->rowId)."'>
                                    <span class='header-cart-item-remove'>xóa</span>
                                </a>
                            </div>
                        </div>
                    </li>"; }
            $list_cart = $list_cart . " <span class='header-cart-item-total mr-3'>
                        <p class='total-price'>Tổng: " . number_format(Cart::total(), 0, ',', '.') ."đ</p>
                    </span>
                </ul>";
            //Kiểm tra sự tồn tại của id nếu có thì cho đặt hàng ngược lại nếu ko thì ko cho đặt hàng phải đăng nhập
            if($customer_id != null) {
                $list_cart = $list_cart . "
                    <div class='header-cart-btn'>
                        <a href='". url('gio-hang') ."' class='header-cart-view-cart btn btn--primary'>Giỏ hàng</a>
                        <a href='". route('cart.order') ."' class='header-cart-view-cart-pay btn btn--primary'>Thanh toán</a>
                    </div>";
            } else {
                $list_cart = $list_cart . "
                    <div class='header-cart-btn'>
                        <a href='". url('gio-hang') ."' class='header-cart-view-cart btn btn--primary'>Giỏ hàng</a>
                        <a href='". route('form.login') ."' class='header-cart-view-cart-pay btn btn--primary'>Thanh toán</a>
                    </div>";
            }
        $data = array(
            //Tổng số lượng giỏ hàng ở header
            'num' => Cart::count(),
            //Chuỗi html dạng json ở header
            'list_cart' => $list_cart
        );
        //Trả dữ liệu của ajax về dạng json
        return response()->json($data);
    }

    //Phương thức hiển thị danh sách giỏ hàng đã thêm
    function show() {
        //Lấy ra tên menu 1
        $list_name_menu_one = Menu::where('name_menu', 'Trang chủ')->first();
        //Lấy ra tên menu 2
        $list_name_menu_two = Menu::where('name_menu', 'Sản phẩm')->first();
        //Lấy ra tên menu 3
        $list_name_menu_tree = Menu::where('name_menu', 'Blog')->first();
        //Danh sách trang menu header
        $list_menus = Page::all();
        //Chuyển hướng
        return view('carts.list', compact( 'list_name_menu_one', 'list_name_menu_two', 'list_name_menu_tree', 'list_menus'));
    }

    //Phương thức xóa sản phẩm trong giỏ hàng
    function delete($rowId) {
        Cart::remove($rowId);
        return redirect('gio-hang');
    }

    //Phương thức xóa toàn bộ sản phẩm trong giỏ hàng
    function deleteAll() {
        Cart::destroy();
        return redirect('gio-hang');
    }

    //Phương thức cập nhập số lượng trong giỏ hàng
    function update(Request $request) {
        //Tổng giá phụ mặc định bằng 0
        $sub_total = 0;
        // cập nhập lại giở hàng theo rowid
        Cart::update($request->input('rowId'), $request->input('num_order'));
        foreach(Cart::content() as $item) {
            if($item->rowId == $request->input('rowId')) {
                $sub_total = number_format($item->total, '0', ',', '.');
                break;
            }
        }
        //Lấy id session đã lưu
        $customer_id = session()->get('id');

        $list_cart = "<h4 class='header-cart-heading'>Sản phẩm đã thêm</h4>";
        $list_cart . "<ul class='header-cart-list-item'>";
            foreach(Cart::content() as $item) {
                $list_cart = $list_cart .
                    "<li class='header-cart-item'>
                        <a href='" . route('product.detail', $item->options->slug) . "' class='header-cart-item-link'>
                            <img src='" . asset($item->options->image) . "' class='header-cart-img'>
                        </a>
                        <div class='header-cart-item-info'>
                            <div class='header-cart-item-head'>
                                <a href='" . route('product.detail', $item->options->slug) . "' class='header-cart-item-link'>
                                    <h5 class='header-cart-item-name'>". Str::of($item->name)->limit(45) ."</h5>
                                </a>
                                <div class='header-cart-item-price-wrap'>
                                    <span class='header-cart-item-price'>". number_format($item->price, '0', ',', '.') ."đ</span>
                                    <span class='header-cart-item-multiply'>x</span>
                                    <span class='header-cart-item-qnt'>". $item->qty ."</span>
                                </div>
                            </div>
                            <div class='header-cart-item-body'>
                                <span class='header-cart-item-description'></span>
                                <a class='del-cart' href='". route('cart.remove', $item->rowId)."'>
                                    <span class='header-cart-item-remove'>xóa</span>
                                </a>
                            </div>
                        </div>
                    </li>";
                }
            $list_cart = $list_cart . " <span class='header-cart-item-total mr-3'>
                        <p class='total-price'>Tổng: " . number_format(Cart::total(), 0, ',', '.') ."đ</p>
                    </span>
                </ul>";
            //Kiểm tra sự tồn tại của id nếu có thì cho đặt hàng ngược lại nếu ko thì ko cho đặt hàng phải đăng nhập
            if($customer_id != null) {
                $list_cart = $list_cart . "
                    <div class='header-cart-btn'>
                        <a href='". url('gio-hang') ."' class='header-cart-view-cart btn btn--primary'>Giỏ hàng</a>
                        <a href='". route('cart.order') ."' class='header-cart-view-cart-pay btn btn--primary'>Thanh toán</a>
                    </div>";
            } else {
                $list_cart = $list_cart . "
                    <div class='header-cart-btn'>
                        <a href='". url('gio-hang') ."' class='header-cart-view-cart btn btn--primary'>Giỏ hàng</a>
                        <a href='". route('form.login') ."' class='header-cart-view-cart-pay btn btn--primary'>Thanh toán</a>
                    </div>";
            }
        $data = array(
            'sub_total' => $sub_total . 'đ',
            'total' =>  number_format(Cart::total(), 0, ',', '.') . 'đ',
            'num' => Cart::count(),
            'list_cart' => $list_cart
        );
        return response()->json($data);
    }

    //Phương thức mua ngay sản phẩm ở giỏ hàng
    function buyNow($id) {
        $product = Product::find($id);
        Cart::add([
            'id' => $product->id,
            'name' => $product->name_product,
            'qty' => 1,
            'price' => $product->price,
            'options' => ['image' => $product->image, 'warehouse' => $product->quantily, 'slug' => $product->slug]
        ]);
        return redirect('gio-hang');
    }

    //Phhương thức thêm đơn hàng mới (khách điền thông tin đặt hàng)
    function order(Request $request) {
        //Lấy id session đã lưu
        $customer_id = session()->get('id');
        //Lấy ra tên menu 1
        $list_name_menu_one = Menu::where('name_menu', 'Trang chủ')->first();
        //Lấy ra tên menu 2
        $list_name_menu_two = Menu::where('name_menu', 'Sản phẩm')->first();
        //Lấy ra tên menu 3
        $list_name_menu_tree = Menu::where('name_menu', 'Blog')->first();
        //Danh sách trang menu header
        $list_menus = Page::all();
        if($customer_id != null) {
            return view('carts.order', compact( 'list_name_menu_one', 'list_name_menu_two', 'list_name_menu_tree', 'list_menus'));
        } else {
            return redirect('gio-hang')->with('error', 'Bạn chưa đăng nhập tài khoản, bạn vui lòng');
        }
    }

    //Phương thức validate dữ liệu khách hàng
    function store(Request $request) {
        //Lấy ra tên menu 1
        $list_name_menu_one = Menu::where('name_menu', 'Trang chủ')->first();
        //Lấy ra tên menu 2
        $list_name_menu_two = Menu::where('name_menu', 'Sản phẩm')->first();
        //Lấy ra tên menu 3
        $list_name_menu_tree = Menu::where('name_menu', 'Blog')->first();
        //Danh sách trang menu header
        $list_menus = Page::all();
        //Validate dữ liệu khách hàng đặt hàng
        $request->validate(
            [
                'fullname' => 'required|string|max:255',
                'email' => 'required',
                'address' => 'required',
                'phone' => 'required',
                'note' => 'max:1000',
            ],
            [
                'required' => ':attribute không được để trống',
                'string' => ':attribute có dữ liệu không hợp lệ',
                'max' => ':attribute chỉ cho phép tối đa 255 kí tự'
            ],
            [
                'fullname' => 'Họ và tên',
                'email' => 'Email',
                'address' => 'Địa chỉ',
                'phone' => 'Số điện thoại',
                'note' => 'Ghi chú'
            ]
        );
        // return $request->all();
        //Kiểm tra email ,chú thích đã tồn tại chưa? nếu chưa thì cho đó là 1 biến khởi tạo
        $email = "";
        if ($request->input('email')) {
            $email = $request->input('email');
        }
        $note = "";
        if ($request->input('note')) {
            $note = $request->input('note');
        }
        //Nếu giỏ hàng có sản phẩm thì insert dữ liệu thông tin khách hàng vào db đồng thời dữ liệu gửi lên có trường email thì cho nó vào biến email khởi tạo trước đó
        if (Cart::count() > 0) {
            $customer = Customer::create([
                'fullname' => $request->input('fullname'),
                'email' => $request->input('email'),
                'address' => $request->input('address'),
                'phone' => $request->input('phone'),
                'note' => $request->input('note'),
            ]);
            //Tạo mã theo cửa hàng unimart, thêm dữ liệu vào bảng khách hàng
            $order_code = "UNM_" . Cart::count() . strtoupper(substr(md5(time()), 0, 4));
            $order = Order::create([
                'order_code' => $order_code,
                'customer_id' => $customer->id,
                'total_quantily' => Cart::count(),
                'total_price' => Cart::total(),
                'status' => 'Đang xử lí',
                'payment' => $request->input('payment'),
            ]);
            //Duyệt mảng đã lưu ở giỏ hàng để thêm vào db
            foreach (Cart::content() as $item) {
                DetailOrder::create([
                    'order_code' => $order_code,
                    'price' => $item->price,
                    'quantily' => $item->qty,
                    'sub_total' => $item->total,
                    'product_id' => $item->id,
                    'total_quantily' => Cart::count(),
                    'total_price' => Cart::total(),
                ]);
            }
            //Lấy ra tổng tiền gửi qua form mail
            $total_order = Order::find($order->id)->total_price;
            //Lấy ra ngày theo đơn hàng gửi qua form mail
            $create_at = Order::find($order->id)->created_at;
            //Thông tin khách hàng truyền tới form gửi mail
            $info_customer = Order::join('customers', 'customers.id', '=', 'orders.customer_id')
                ->select('customers.*')
                ->where('order_code',  $order_code)
                ->get();
            //Chi tiết đơn hàng truyền tới form gửi mail
            $detail_order = DetailOrder::join('orders', 'orders.order_code', '=', 'detail_orders.order_code')
                ->join('products', 'products.id', '=', 'detail_orders.product_id')
                ->select('products.name_product', 'detail_orders.quantily', 'detail_orders.price', 'detail_orders.sub_total')
                ->where('orders.order_code',  $order_code)
                ->get();
                session([
                'order_id' => $order->id,
                'order_code' => $order_code,
                'total_order' => $total_order
                ]);
            //Tạo dữ liệu truyền tới giao diện thông báo gửi mail đi
            $data = array(
                'total_order' => $total_order,
                'order_code' => $order_code,
                'create_at' => $create_at,
                'info_customer' => $info_customer,
                'detail_order' => $detail_order
            );
            //Nếu mảng mail không trống thì gửi mail cho khách
            if ($email != "") {
                Mail::to($email)->send(new SendInfoOrder($data));
            }
            //Sau khi gửi mail xong thì xóa giỏ hàng đồng thời chuyển hướng qua trang phương thức thanh toán
            Cart::destroy();
            return redirect('gio-hang/dat-hang/thanh-cong');
        } else {
            //Nếu chưa điền thông tin đặt hàng thì hiển thị giao diên đặt hàng
            return view('carts.order', compact('list_name_menu_one', 'list_name_menu_two', 'list_name_menu_tree', 'list_menus'));
        }
    }

    //Phương thức đặt hàng thành công
    function success() {
        //Lấy id session đã lưu
        $customer_id = session()->get('id');
        //Lấy ra tên menu 1
        $list_name_menu_one = Menu::where('name_menu', 'Trang chủ')->first();
        //Lấy ra tên menu 2
        $list_name_menu_two = Menu::where('name_menu', 'Sản phẩm')->first();
        //Lấy ra tên menu 3
        $list_name_menu_tree = Menu::where('name_menu', 'Blog')->first();
        //Danh sách trang menu header
        $list_menus = Page::all();
        $order_id = session('order_id');
        $order_code = session('order_code');
        $info_customer = Order::join('customers', 'customers.id', '=', 'orders.customer_id')
            ->select('customers.*', 'orders.order_code', 'orders.status', 'orders.payment')
            ->where('order_code',  $order_code)
            ->get();
        $detail_order = DetailOrder::join('orders', 'orders.order_code', '=', 'detail_orders.order_code')
            ->join('products', 'products.id', '=', 'detail_orders.product_id')
            ->select('products.name_product', 'detail_orders.quantily', 'detail_orders.price', 'detail_orders.sub_total')
            ->where('orders.order_code',  $order_code)
            ->get();
        $total_order = session('total_order');
        $data = array(
            'order_id' => $order_id,
            'order_code' => $order_code
        );
        if($customer_id != null) {
            Mail::to('htinh7444@gmail.com')->send(new NewOrder($data));
            return view('carts.success', compact('list_name_menu_one', 'list_name_menu_two', 'list_name_menu_tree', 'list_menus', 'order_code', 'info_customer', 'detail_order', 'total_order'));
        } else {
            return redirect('gio-hang')->with('error', 'Bạn chưa đăng nhập tài khoản, bạn vui lòng');
        }
    }

    //Phương thức in ra đơn hàng
    public function print_order ($checkout_code) {
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($this->print_order_convert($checkout_code));
        return $pdf->stream();
    }

    //Phương thức chuyển đổi in ra đơn hàng từ mã đơn hàng
    public function print_order_convert ($checkout_code) {
        //Lấy ra đơn hàng mới để từ đó suy ra khách hàng nào mua
        $order_new = Order::where('order_code', $checkout_code)->get();
        //Duyệt và lấy ra id khách hàng
        foreach($order_new as $order) {
            $customer_id = $order->customer_id;
        }
        //Tìm kiếm thông tin khách hàng theo id
        $customer_info = Customer::join('orders', 'orders.customer_id', '=', 'customers.id')
            ->select('customers.*', 'orders.order_code', 'orders.status', 'orders.payment')
            ->where('customers.id', $customer_id)
            ->get();
        //Chi tiết đơn hàng in ra
        $order_details_product = DetailOrder::join('orders', 'orders.order_code', '=', 'detail_orders.order_code')
            ->join('products', 'products.id', '=', 'detail_orders.product_id')
            ->select('products.name_product', 'products.image', 'detail_orders.quantily', 'detail_orders.price', 'detail_orders.sub_total')
            ->where('orders.order_code', $checkout_code)
            ->get();
        //Lấy tổng tiền từ seesion lưu lại để in ra đơn hàng
        $total_order = session('total_order');
        //khởi tạo mảng rỗng khởi tạo
        $output = "";
        $output .= "
                <style>
                    body {
                        font-size: 0.7rem;
                        font-family: DejaVu Sans;
                    }
                    .text-center {
                        text-align: center;
                    }
                    .item-height {
                        margin-bottom: 1px;
                        line-height: 1.2;
                    }
                    .table-heading {
                        background-color: #c3e6cb;
                    }
                    .margin-top {
                        margin-top: 15px;
                    }
                    .box-head-title {
                        margin-bottom: 8px;
                    }
                </style>
                <div>
                    <h2 class='text-center'>ĐƠN HÀNG</h2>";
                foreach ($customer_info as $info) {
                $output .= "<p class='item-height'><strong>Kính gửi</strong>: ". $info->fullname ."</p>
                    <p class='item-height'><strong>Cửa hàng</strong>: UNIMART.VN</p>
                    <p class='item-height box-head-title'><strong>Unimart Store!</strong> xin gửi lời chào tốt đẹp đến quý khách hàng chúng tôi xin phép gửi đơn hàng để quý khách hàng tham khảo, chi tiết như sau:</p>
                    <table border='1'>
                        <thead class='table-heading'>
                            <tr>
                                <th class='text-center' scope='col'>Mã đơn hàng</th>
                                <th class='text-center' scope='col'>Số điện thoại</th>
                                <th class='text-center' scope='col'>Địa chỉ</th>
                                <th class='text-center' scope='col'>Email</th>
                                <th class='text-center' scope='col'>Ghi chú</th>
                                <th class='text-center' scope='col'>Tình trạng</th>
                                <th class='text-center' scope='col'>Ngày đặt</th>
                                <th class='text-center' scope='col'>Hình thức thanh toán</th>
                            </tr>
                        </thead>
                        <tbody>";
                $output .= "<tr>
                                <td>". $info->order_code ."</td>
                                <td>". $info->phone ."</td>
                                <td>". $info->address ."</td>
                                <td>". $info->email ."</td>
                                <td>". $info->note ."</td>
                                <td>". $info->status ."</td>
                                <td>". $info->created_at->format('H:i d-m-Y') ."</td>
                                <td>". $info->payment ."</td>
                            </tr>";
                }
                $output .= "</tbody>
                    </table>
                    <h4 class='box-head-title'>
                        Thông tin sản phẩm
                    </h4>
                    <table border='1'>
                        <thead class='table-heading'>
                            <tr>
                                <th class='table-title text-center' scope='col'>Tên sản phẩm</th>
                                <th class='table-title text-center' scope='col'>Số lượng</th>
                                <th class='table-title text-center' scope='col'>Giá</th>
                                <th class='table-title text-center' scope='col'>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>";
                        foreach ($order_details_product as $info) {
                    $output .= " <tr>
                                    <td class='text-center'>". $info->name_product ."</td>
                                    <td class='text-center'>". $info->quantily ."</td>
                                    <td class='text-center'>". number_format($info->price, 0,',','.') ."đ</td>
                                    <td class='text-center'>". number_format($info->sub_total, 0,',','.') ." đ</td>
                                </tr>";
                        }
                    $output .= " <tr>
                                    <th scope='col' colspan='1' class='table-title text-center'>Tổng tiền</th>
                                    <td colspan='4' class='text-center'>". number_format($total_order, 0,',','.') ."đ</td>
                                </tr>
                                </tr>
                        </tbody>
                    </table>
                    <div class='margin-top'>
                        <span class='item-height'>Mọi thắt mắc về <strong>Đơn hàng</strong> vui lòng liên hệ <strong>Hà Quan Tính</strong> để được hỗ trợ<p>
                        <span class='item-height'><i>Điện thoại: 0377953849</i><p>
                        <span class='item-height'><i>Email: htinh7444@gmail.com</i><span>
                    </div>
                </div>
            ";
        return $output;
    }
}
