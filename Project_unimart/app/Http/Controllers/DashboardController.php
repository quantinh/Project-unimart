<?php

namespace App\Http\Controllers;

use App\Order;
use App\Customer;
use App\Product;
use App\ProductCat;
use App\DetailOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //Phương thức khởi tạo
    function __construct()
    {
        //Kiểm tra trước khi được vào trang này phải có quyền là 1(Administrator) và quyền thứ 3(SliderBanner)
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'dashboard']);
            return $next($request);
        });
    }

    //Phương thức danh sách bảng điều khiển
    function show()
    {
        //Tạo mảng lớn đếm tất cả thông tin đang xử lí (...) Đang vận chuyển (...) Thành công (...) Hủy đơn (...)
        $num_orders = array(
            //Đếm tất cả đơn hàng có trạng thái đang xử lí
            'processing' => Order::where('status', 'Đang xử lí')->count(),
            //Đếm tất cả đơn hàng có trạng thái đang vận chuyển
            'transport' => Order::where('status', 'Đang vận chuyển')->count(),
            //Đếm tất cả đơn hàng có trạng thái thành công
            'success' => Order::where('status', 'Thành công')->count(),
            //Đếm tất cả đơn hàng trong thùng rác
            'cancel' => Order::onlyTrashed()->where('status', 'Hủy đơn')->count(),
        );
        //Tạo mảng lớn lấy dữ liệu đã tính số liệu thống kê
        $statictis = array(
            //Tính tổng tiền bán được của sản phẩm đó
            'total_sale' => Order::sum('total_price'),
            //Tính tổng số lượng sản phẩm còn trong kho
            'total_quantily' => Product::sum('quantily'),
            //Tính tổng số lượng sản phẩm đã bán
            'total_sold' => Order::where('status', 'Thành công')->sum('total_quantily'),
            //Tính tổng số lượng lợi nhuận bằng tổng tiền * 2
            'profit' => Order::sum('total_price') * 0.2
        );
        //Danh sách màu trạng thái
        $color = array(
            'Đang xử lí' => 'primary',
            'Đang vận chuyển' => 'warning',
            'Hủy đơn' => 'danger',
            'Thành công' => 'success',
        );
        //Tạo mảng chứa các giá trị phần tử được lấy xuống từ db kể cả thùng rác để hiển thị dữ liệu
        $list_order_dashboards = Order::withTrashed()
            //Join vào bảng khách hàng để có được id của khách hàng => đơn hàng đó của khách hàng nào
            ->join('customers', 'customers.id', '=', 'orders.customer_id')
            //Chọn tất cả bảng ghi của bảng đơn hàng và lấy ra được họ tên của bảng khách hàng khi đã join
            ->select('orders.*', 'customers.fullname')
            //Gía trị được gán ở trên sẽ lấy ra đơn hàng mới nhất sắp xếp theo thứ tự giảm dần
            ->orderby('created_at', 'desc')
            ->paginate(10);
            //return $list_order_dashboards;
        return view('admin.dashboard', compact('num_orders', 'statictis', 'color', 'list_order_dashboards'));
    }
}
