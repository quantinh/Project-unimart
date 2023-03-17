<?php

namespace App\Http\Controllers;

use App\Product;
use App\Customer;
use App\Order;
use App\User;
use App\DetailOrder;
use Illuminate\Support\Facades\DB; //Phải khai báo khi tạo model theo kiểu Query builder
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminOrderController extends Controller
{
    //Phương thức khởi tạo
    function __construct()
    {
        //Kiểm tra trước khi được vào trang này phải có quyền là 1(Administrator) và quyền thứ 2(ProductOrder)
        $this->middleware('CheckRole2');
        //Tạo ràng buộc kèm tham số next
        $this->middleware(function ($request, $next) {
            // thêm key và value cho session
            session(['module_active' => 'order']);
            return $next($request);
        });
    }

    //Phương thức hiển thị danh sách đơn hàng
    function list(Request $request)
    {
        $keyword = "";
        $status_order = "";
        //Tạo danh sách các hành động trên selected
        $list_action = array(
            'Đang xử lí',
            'Đang vận chuyển',
            'Thành công',
            'Hủy đơn',
            'Xóa vĩnh viễn'
        );
        //Danh sách màu trạng thái
        $color = array(
            'Đang xử lí' => 'primary',
            'Đang vận chuyển' => 'warning',
            'Hủy đơn' => 'danger',
            'Thành công' => 'success',
        );
        //Tạo mảng lớn đếm tất cả thông tin Tất cả (...)  đang xử lí (...) Đang vận chuyển (...) Thành công (...) Hủy đơn (...)
        $count = array(
            //Đếm tất cả đơn hàng kể cả trong thùng rác
            'all' => Order::withTrashed()->count(),
            //Đếm tất cả đơn hàng có trạng thái đang xử lí
            'processing' => Order::where('status', 'Đang xử lí')->count(),
            //Đếm tất cả đơn hàng có trạng thái đang vận chuyển
            'transport' => Order::where('status', 'Đang vận chuyển')->count(),
            //Đếm tất cả đơn hàng có trạng thái thành công
            'success' => Order::where('status', 'Thành công')->count(),
            //Đếm tất cả đơn hàng trong thùng rác
            'cancel' => Order::onlyTrashed()->where('status', 'Hủy đơn')->count(),
        );
        //$Field = 'created_at';
        $field = 'created_at';
        //Giá trị khởi tạo sẽ $Value = 'desc';
        $value = 'desc';
        //Nếu dữ liệu gửi lên url có orderby = 'total_asc' thì gán cho truy vấn orderby ($field = 'total_price', $value = 'asc');
        if ($request->orderby == 'total_asc') {
            $field = 'total_price';
            $value = 'asc';
            //Ngược lại nếu dữ liệu gửi lên có orderby = 'total_desc' thì gán cho truy vấn orderby ($field = 'total_price', $value = 'desc');
        } else if ($request->orderby == 'total_desc') {
            $field = 'total_price';
            $value = 'desc';
            //Ngược lại nếu dữ liệu gửi lên có orderby =  'asc' thì gán cho truy vấn orderby($field = 'created_at', $value = 'asc')
        } else if ($request->orderby == 'asc') {
            $value = $request->orderby;
        }
        //Tạo 1 mảng với hai phần tử là điều kiện cho orderby
        $order_by = ['orders.id', 'desc'];
        //Nếu từ có từ khóa search thì gửi dữ liệu theo key đó
        if ($request->input('keyword')) {
            $keyword = $request->input('keyword');
        }
        //Nếu dữ liệu có giá trị orderby thì gán cho orderby key và value
        if ($request->input('orderby')) {
            $order_by[0] = 'total_price';
            $order_by[1] = $request->input('orderby');
        }
        //Nếu yêu cầu thay đổi trạng thái thì thay đổi trạng thái theo từng trường hợp
        if ($request->input('status')) {
            $status_order = $request->input('status');
            switch ($status_order) {
                case "processing":
                    $status = "Đang xử lí";
                    $list_action = array(
                        'Đang vận chuyển',
                        'Thành công',
                        'Hủy đơn'
                    );
                    break;
                case "transport":
                    $status = "Đang vận chuyển";
                    $list_action = array(
                        'Thành công',
                        'Hủy đơn',
                        'Đang xử lí'
                    );
                    break;
                case "success":
                    $status = "Thành công";
                    $list_action = array(
                        'Đang xử lí',
                        'Đang vận chuyển',
                        'Hủy đơn',
                        'Xóa vĩnh viễn'
                    );
                    break;
                case "cancel":
                    $status = "Hủy đơn";
                    $list_action = array(
                        'Đang xử lí',
                        'Đang vận chuyển',
                        'Thành công',
                        'Xóa vĩnh viễn'
                    );
                    break;
            }
            //Nếu dữ liệu url không có giá trị thì lấy dữ liệu và trả dữ liệu về view
            $list_orders = Order::withTrashed()->join('customers', 'customers.id', '=', 'orders.customer_id')
                ->select('orders.*', 'customers.fullname')
                ->where('status', "{$status}")
                ->orderby($order_by[0], $order_by[1])
                ->paginate(10);
            return view('admin.orders.list', compact('keyword', 'status_order', 'list_action', 'color', 'count', 'list_orders'));
        }
        //Tạo môt biến chứa các giá trị phần tử được lấy xuống từ db kể cả thùng rác để hiển thị dữ liệu
        $list_orders = Order::withTrashed()
            //Join vào bảng khách hàng để có được id của khách hàng
            ->join('customers', 'customers.id', '=', 'orders.customer_id')
            //Chọn tất cả bảng ghi của bảng đơn hàng và lấy ra được họ tên của bảng khách hàng khi đã join
            ->select('orders.*', 'customers.fullname')
            //Lấy mã đơn hàng theo từ khóa khi search
            ->where('order_code', 'LIKE', "%{$keyword}%")
            //Lấy theo trạng thái khi search
            ->orwhere('status', 'LIKE', "%{$keyword}%")
            //Lấy ngày cập nhập từ bảng đơn hàng theo từ khóa đã search từ form input
            ->orwhere('orders.updated_at', 'LIKE', "%{$keyword}%")
            //Gía trị được gán ở trên sẽ lấy ra đơn hàng mới nhất sắp xếp theo thứ tự giảm dần
            ->orderby($field, $value)
            //Phân trang 20 đơn hàng trên 1 trang
            ->paginate(10);
        //Trả lại cho view với những dữ liệu trên để hiển thị
        return view('admin.orders.list', compact('keyword', 'status_order', 'list_action', 'color', 'count', 'list_orders'));
    }

    //Phương thức thao tác khi check với list đơn hàng
    function action(Request $request)
    {
        //Kiểm tra xem đã chọn dữ liệu chưa nếu có thì lấy giá trị từ name list_check['id'= 1, ....]
        if ($request->input('list_check')) {
            //Từ đó có được list id đã check
            $list_check = $request->input('list_check');
            //Kiểm tra nếu đã chọn thao tác thì action lúc này sẽ có giá trị
            if ($request->input('action')) {
                //Gía trị sẽ được gán $action = 'Đang vận chuyển', ...'thành công', ...'
                $action = $request->input('action');
                //Kiểm tra nếu giá trị action không phải là xóa vĩnh viễn có thể là (thành công, đang xử lí, đang vận chuyển, hủy đơn, xóa vĩnh viễn)
                if ($action != "Xóa vĩnh viễn") {
                    //TH1: Nếu thao tác action có giá trị là thành công thì
                    if ($action == "Thành công") {
                        //Duyệt qua list đã check là các phần tử id đã check từ id đó suy ra được đơn hàng
                        foreach ($list_check as $value) {
                            //Lấy ra đơn hàng đó kể cả trong thùng rác theo các id đã check trong list
                            $order = Order::withTrashed()->where('id', $value)->get();
                            //Từ đơn hàng thì ta có thể duyệt qua từng đơn hàng và kiểm tra được trường status có trạng thái là thành công thì chuyển hướng kèm thông báo
                            foreach ($order as $info) {
                                //Thì chuyển hướng thông báo
                                if ($info->status == "Thành công") {
                                    return redirect('admin/orders/list')->with('order', 'Không thể cập nhật trạng thái thành công cho đơn đã thành công');
                                }
                            }
                            //Từ đơn hàng thì có thể duyệt qua tiếp để kiểm tra trường của đơn hàng kế tiếp xem có trùng là trạng thái hủy đơn ko nếu trung thì
                            foreach ($order as $info) {
                                //Nếu trạng thái đơn hàng lúc này đang là hủy đơn thì khôi phục lại vì xóa tạm(hủy đơn) để cập nhập thành trạng thái thành công khi đang là hủy đơn
                                if ($info->status == "Hủy đơn") {
                                    DetailOrder::onlyTrashed()->where('order_code', $info->order_code)->restore();
                                    Order::onlyTrashed()->where('order_code', $info->order_code)->restore();
                                }
                                //Số lượng đơn hàng lúc này từ hủy = thành công khi chọn thì cập nhập lại số lượng trong kho lấy ra sản phẩm a từ product_id, lấy ra số lượng sản phẩm a tức là khách hàng đã mua sản phẩm và số lương cần mua
                                $quantily_order = DetailOrder::select('detail_orders.product_id', 'detail_orders.quantily')
                                    ->where('order_code', $info->order_code)
                                    ->get();
                                //Duyệt qua phần tử số lượng và sản phẩm a khách đã mua để suy ra được sản phẩm còn trong kho => số lượng tồn kho
                                foreach ($quantily_order as $item) {
                                    //Từ sản phẩm a khách đặt suy ra được sản phẩm còn trong kho và số lượng tồn kho là bao nhiêu
                                    $num_product = Product::find($item->product_id)->quantily;
                                    //Cập nhập lại số lượng sản phẩm tồn kho = số lượng tồn kho - số lượng khách đã đặt
                                    Product::where('id', $item->product_id)->update(['quantily' => $num_product - $item->quantily]);
                                }
                            }
                            //Cập nhập lại trường status thành = thành công theo action đã chọn
                            Order::where('id', $value)->update(['status' => $action]);
                        }
                        return redirect('admin/orders/list')->with('order', 'Thay đổi trạng thái đơn hàng thành công');
                    } else {
                        //Nếu chọn action đang xử lí, đang vận chuyển, hủy đơn, ...
                        //Duyệt danh sách check lấy ra tất cả những phần tử id đã check
                        foreach ($list_check as $value) {
                            //Lấy tất cả đơn hàng theo id đã check
                            $order = Order::withTrashed()->where('id', $value)->get();
                            //Từ đơn hàng thì ta có thể duyệt qua từng đơn hàng và kiểm tra được trường status có trạng thái là hủy đơn thì
                            foreach ($order as $info) {
                                //Khôi phục lại đơn hàng bị xóa tạm
                                if ($info->status == "Hủy đơn") {
                                    DetailOrder::onlyTrashed()->where('order_code', $info->order_code)->restore();
                                    Order::onlyTrashed()->where('order_code', $info->order_code)->restore();
                                }
                                //Nếu đơn hàng có trạng thái là thành công mà thao tác không phải là thành công thì thì các trường hợp khác sẽ coi như chưa tới đk tay khách hàng thì số lượng sản phẩm tồn kho không bị thay đổi
                                if ($info->status == "Thành công") {
                                    //Lấy ra sản phẩm a, số lượng sản phẩm a theo chi tiết đơn hàng
                                    $quantily_order = DetailOrder::select('detail_orders.product_id', 'detail_orders.quantily')
                                        ->where('order_code', $info->order_code)
                                        ->get();
                                    //Duyệt qua phần tử số lượng và sản phẩm a khách đã đặt để suy ra được sản phẩm còn trong kho => số lượng tồn kho
                                    foreach ($quantily_order as $item) {
                                        //Từ sản phẩm a khách đặt suy ra được sản phẩm còn trong kho và số lượng tồn kho là bao nhiêu
                                        $num_product = Product::find($item->product_id)->quantily;
                                        //Cập nhập lại số lượng sản phẩm tồn kho = số lượng tồn kho + số lượng khách đã đặt = (giữ nguyên số lượng cũ)
                                        Product::where('id', $item->product_id)->update(['quantily' => $num_product + $item->quantily]);
                                    }
                                }
                            }
                            //Cập nhập lại trường status thành = thành công theo action đã chọn
                            Order::where('id', $value)->update(['status' => $action]);
                            //Nếu thao tác chọn hủy đơn thì xóa tạm theo id đơn hàng có trong list_check
                            if ($action == "Hủy đơn") {
                                Order::where('id', $value)->delete();
                            }
                        }
                        return redirect('admin/orders/list')->with('order', 'Thay đổi trạng thái đơn hàng thành công');
                    }
                } else {
                    //Nếu "action xóa vĩnh viễn" thì cho phép thực thi trên trạng thái hủy đơn(đơn hàng bị hủy khách ko đặt nữa thì xóa vĩnh viễn) hoặc thành công(có thể xóa vĩnh viễn vì nó ko cần đến nữa)
                    //Chạy duyệt danh sách id đã check
                    foreach ($list_check as $value) {
                        //Lấy ra đơn hàng kể cả trong thùng ra theo id đã check
                        $order = Order::withTrashed()->where('id', $value)->get();
                        //Nếu đơn hàng có trạng thái thì kiểm tra trạng thái nếu đang xử lí or đang vận chuyển thì chuyển hướng ko đk thực thi
                        foreach ($order as $info) {
                            if ($info->status == "Đang xử lí" || $info->status == "Đang vận chuyển") {
                                return redirect('admin/orders/list')->with('order', 'Xóa vĩnh viễn chỉ áp dụng cho trạng thái đơn Thành công hoặc Hủy đơn. Vui lòng chọn lại!');
                            }
                        }
                    }
                    //Chạy duyệt danh sách id đã check
                    foreach ($list_check as $value) {
                        //Từ id đó lấy tất cả đơn hàng kể cả trong thùng rác (bị hủy)
                        $order = Order::withTrashed()->where('id', $value)->get();
                        //Chạy duyệt nếu trạng thái đơn hàng là hủy , thành công thì cho phép xóa
                        foreach ($order as $info) {
                            //Xóa bên ngoài thùng rác chi tiết đơn hàng bị hủy
                            if ($info->status == "Hủy đơn") {
                                //Xóa tạm bên ngoài nếu hủy đơn vẫn còn bên ngoài thùng rác
                                DetailOrder::withoutTrashed()->where('order_code', $info->order_code)->delete();
                                //Xóa vĩnh viễn trong thùng rác chi tiết đơn hàng bị hủy vì bị hủy (là nằm trong thùng rác)
                                DetailOrder::onlyTrashed()->where('order_code', $info->order_code)->forceDelete();
                                //Xóa vĩnh viễn trong thùng rác đơn hàng bị hủy vì bị hủy (là nằm trong thùng rác)
                                Order::onlyTrashed()->where('order_code', $info->order_code)->forceDelete();
                                //Xóa tạm khách hàng đó
                                Customer::where('id', $info->customer_id)->delete();
                            }
                            //Nếu trạng thái thành công mà muốn xóa vĩnh viễn đơn hàng đó thì
                            if ($info->status == "Thành công") {
                                //Xóa tạm chi tiết đơn hàng đó bên ngoài thùng rác
                                DetailOrder::withoutTrashed()->where('order_code', $info->order_code)->delete();
                                //Xóa tạm đơn hàng đó ngoài thùng rác
                                Order::withoutTrashed()->where('order_code', $info->order_code)->delete();
                                //Xóa vĩnh viễn trong thùng rác chi tiết đơn hàng
                                DetailOrder::onlyTrashed()->where('order_code', $info->order_code)->forceDelete();
                                //Xóa vĩnh viễn trong thùng rác đơn hàng đó
                                Order::onlyTrashed()->where('order_code', $info->order_code)->forceDelete();
                                //Xóa tạm khách hàng đó
                                Customer::where('id', $info->customer_id)->delete();
                            }
                        }
                    }
                    //Nếu xóa thành công thì chuyển hướng thông báo
                    return redirect('admin/orders/list')->with('order', 'Thực hiện xóa vĩnh viễn thành công');
                }
            } else {
                //Nếu action ko có giá trị lấy xuống thì chuyền hướng kèm thông báo
                return redirect('admin/orders/list')->with('order', 'Bạn chưa chọn trạng thái');
            }
            //Nếu list_check chưa có id nào là chưa check thì chuyển hướng kèm thông báo
        } else {
            return redirect('admin/orders/list')->with('order', 'Không có đơn hàng nào được check');
        }
    }

    //Phương thức hủy đơn hàng
    function cancel($id)
    {
        //Lấy mã đơn hàng theo id đơn hàng đã chọn
        $order_code = Order::find($id)->order_code;
        //Cập nhập trạng thái hủy đơn hàng thành hủy bỏ theo mã đơn hàng đã lấy ra
        Order::where('order_code', $order_code)->update(['status' => 'Hủy đơn']);
        //Xóa tạm đơn hàng vào thùng rác và chuyển hướng kèm thông báo
        Order::where('order_code', $order_code)->delete();
        return redirect('admin/orders/list')->with('status', 'Hủy đơn hàng thành công');
    }

    //Xóa vĩnh viễn đơn hàng
    function delete($id)
    {
        //Từ id đã chọn lấy ra được trạng thái đơn hàng đó để xác định trạng thái đơn hàng đó
        $status_order = Order::withTrashed()->find($id)->status;
        //Từ id đã chọn lấy được mã đơn hàng đễ xóa theo mã đơn hàng khách đã đặt
        $order_code = Order::withTrashed()->find($id)->order_code;
        //Từ id đã chọn lấy được id của khách hàng đó để xác định xóa tạm khách hàng đó vào thùng rác
        $customer_id = Order::withTrashed()->find($id)->customer_id;
        //Kiểm tra trạng thái đơn hàng, nếu là thành công
        if ($status_order == "Thành công") {
            //Thì xóa đơn hàng bên ngoài theo mã ở bảng chi tiết, và bảng đơn hàng
            DetailOrder::withoutTrashed()->where('order_code', $order_code)->delete();
            Order::withoutTrashed()->where('order_code', $order_code)->delete();
            //Xóa tiếp trong thùng rác nếu đơn hàng còn trong thùng rác ở bảng đơn hàng, chi tiết
            DetailOrder::onlyTrashed()->where('order_code', $order_code)->forceDelete();
            Order::onlyTrashed()->where('order_code', $order_code)->forceDelete();
            //Xóa tạm khách hàng đó theo id khách hàng và chuyển hướng
            Customer::find($customer_id)->delete();
            return redirect('admin/orders/list')->with('order', 'Xóa vĩnh viễn đơn hàng thành công');
        }
        //Nếu các trạng thái khác như (đang xử lí, đang vận chuyển, hủy đơn) thì Thực hiện xóa tạm theo mã đơn hàng bảng chi tiết
        DetailOrder::where('order_code', $order_code)->delete();
        //Nếu còn tồn tại trong thùng rác thì xóa vĩnh viễn chi tiết đơn hàng, đơn hàng đó ở bảng đơn hàng, bảng chi tiết
        DetailOrder::onlyTrashed()->where('order_code', $order_code)->forceDelete();
        Order::onlyTrashed()->where('order_code', $order_code)->forceDelete();
        //Xóa tạm khách hàng đó và thực hiện chuyển hướng thành công
        Customer::find($customer_id)->delete();
        return redirect('admin/orders/list')->with('order', 'Xóa vĩnh viễn đơn hàng thành công');
    }

    //Phương thức hiển thị chi tiết đơn hàng
    function detail($id)
    {
        //Lấy id đơn hàng của khách hàng để cập nhập dữ liệu cho đơn hàng đó
        $id_order = Order::withTrashed()->find($id)->id;
        //Lây mã đơn hàng của khách hàng xuống
        $order_code = Order::withTrashed()->find($id)->order_code;
        //Lấy ra trạng thái đơn hàng hiện tại để hiển thị trên view
        $status_order = Order::withTrashed()->find($id)->status;
        //Danh sách màu trạng thái
        $color = array(
            'Đang xử lí' => 'primary',
            'Đang vận chuyển' => 'warning',
            'Hủy đơn' => 'danger',
            'Thành công' => 'success',
        );
        // return $status_order;
        //Trạng thái sẽ có theo điều kiện sau nếu trạng thái có giá trị là đang xử lí thì lưu trữ sẵn những action trong mảng để thay đổi giá trị khi cần
        switch ($status_order) {
            case "Đang xử lí":
                $list_action = array(
                    'Đang vận chuyển',
                    'Thành công',
                    'Hủy đơn'
                );
                break;
            case "Đang vận chuyển":
                $list_action = array(
                    'Thành công',
                    'Hủy đơn',
                    'Đang xử lí'
                );
                break;
            case "Thành công":
                $list_action = array(
                    'Đang xử lí',
                    'Đang vận chuyển',
                    'Hủy đơn',
                    'Xóa vĩnh viễn'
                );
                break;
            case "Hủy đơn":
                $list_action = array(
                    'Đang xử lí',
                    'Đang vận chuyển',
                    'Thành công',
                    'Xóa vĩnh viễn'
                );
                break;
        }
        //Bảng thông tin khách hàng cần những dữ liệu như Họ và tên, Mã đơn hàng, địa chỉ, sđt, email, ngày đặt hàng, ghi chú
        //Lấy tất cả dữ liệu của bảng orders gồm cả trong thùng rác (hủy đơn hàng),
        $info_customer = Order::withTrashed()
            //Join để bảng khách hàng có id tương đương với customer_id của bảng đơn hàng
            ->join('customers', 'customers.id', '=', 'orders.customer_id')
            //Lấy ra ngày tháng, mã đơn hàng, tổng tiền, tổng giá, id đơn hàng, bảng khách hàng thì lấy hết các trường dữ liệu
            ->select('orders.created_at as time', 'orders.order_code', 'orders.total_quantily', 'orders.total_price', 'orders.id as order_id', 'customers.*')
            //Điều kiện lấy ra hết là đơn hàng phải theo id đã chọn
            ->where('orders.id', $id)
            ->get();
        //Để hiển thị được bảng chi tiết đơn hàng thì phải join với bảng đơn hàng lấy được mã sản phẩm tương đương với mã đơn hàng chi tiết
        $list_detail_products = DetailOrder::join('orders', 'orders.order_code', '=', 'detail_orders.order_code')
            //Join tiếp với bảng sản phẩm lấy được id sản phẩm tương đương với id sản phẩm của bảng chi tiết
            ->join('products', 'products.id', '=', 'detail_orders.product_id')
            //Chọn bảng sản phẩm lấy ra tên, hình ảnh, bảng chi tiết thì lấy hết các trường dữ liệu
            ->select('products.name_product', 'products.image', 'detail_orders.*')
            //Điều kiện là mã đơn hàng của bảng đơn hàng phải giống của mã đơn hàng bảng chi tiết
            ->where('orders.order_code', $order_code)
            ->get();
        // return $list_detail_products;
        return view('admin.orders.detail', compact('id_order', 'status_order', 'list_action', 'info_customer', 'list_detail_products', 'color'));
    }

    //Phương thức cập nhập đơn hàng thay đổi trạng thái chi tiết đơn hàng
    function update(Request $request, $id)
    {
        //Từ id đơn hàng đã chọn lấy được trạng thái đơn hàng đó
        $status_order = Order::withTrashed()->find($id)->status;
        //Từ id đơn hàng đã chọn lấy được mã đơn hàng
        $order_code = Order::withTrashed()->find($id)->order_code;
        //Nếu dữ liệu gửi lên action có giá trị thì $action = giá trị đã chọn selected
        if ($request->input('action')) {
            $action = $request->input('action');
            //Nếu trạng thái đơn hàng đang là hủy đơn mà chúng ta muốn đổi thành trạng thái khác (như thành công, đang vận chuyển, đang xử lí, hủy đơn, xóa vĩnh viễn) thì khôi phục lại từ trash cả đơn hàng và chi tiết đơn hàng
            if ($status_order == "Hủy đơn") {
                DetailOrder::onlyTrashed()->where('id', $id)->restore();
                Order::onlyTrashed()->where('id', $id)->restore();
            }
            //Nếu trạng thái đơn hàng đang là thành công thì chúng ta lấy sản phẩm a và số lượng sản phẩm a đã đặt từ bảng chi tiết
            if ($status_order == "Thành công") {
                $quantily_order = DetailOrder::select('detail_orders.product_id', 'detail_orders.quantily')
                    ->where('order_code', $order_code)
                    ->get();
                //Duyệt qua hai phần từ sản phẩm a, số lượng đã đặt để tìm ra số lượng sản phẩm còn trong kho
                foreach ($quantily_order as $item) {
                    //Từ sản phẩm a mà khách đã đặt ta suy được theo product_id sản phẩm a của hệ thống và lấy được số lượng sản phẩm đó trong hệ thống từ bảng product
                    $num_product = Product::find($item->product_id)->quantily;
                    //Cập nhập lại số lượng sản phẩm tồn kho hiện tại khi khách đã chuyển trạng thái (chưa tới tay khách hàng) = số lượng tồn kho + số lượng khách đã đặt = (giữ nguyên số lượng cũ)
                    Product::where('id', $item->product_id)
                        ->update(['quantily' => $num_product + $item->quantily]);
                }
            }
            //Nếu chúng ta thao tác xóa vĩnh viễn thì kiểm tra xem đơn hàng đó
            if ($action == "Xóa vĩnh viễn") {
                //Nếu đơn hàng đó đang có trạng thái "thành công" mà thao tác xóa vĩnh viễn thì xóa bên ngoài thùng rác
                if ($status_order == "Thành công") {
                    DetailOrder::withoutTrashed()->where('order_code', $order_code)->delete();
                    Order::withoutTrashed()->where('order_code', $order_code)->delete();
                }
                //Nếu trạng thái đơn hàng là "hủy đơn" tức là trong thùng rác) mà thao tác chúng ta chọn xóa vĩnh viễn thì xóa vĩnh viễn đơn hàng đó (trong thùng rác đói với đơn hàng bị hủy)
                DetailOrder::onlyTrashed()->where('order_code', $order_code)->forceDelete();
                Order::onlyTrashed()->where('order_code', $order_code)->forceDelete();
                return redirect('admin/orders/list')->with('status', 'Xóa vĩnh viễn đơn hàng ' . $order_code . ' thành công');
            }
            //Nếu thao tác chúng ta muốn là thành công thì lấy lấy số lượng sản phẩm a đã đặt lấy ra số lượng đã đặt
            if ($action == "Thành công") {
                //Lấy ra sản phẩm a, số lượng sản phẩm a theo chi tiết đơn hàng
                $quantily_order = DetailOrder::select('detail_orders.product_id', 'detail_orders.quantily')
                    ->where('order_code', $order_code)
                    ->get();
                //Duyệt qua phần tử số lượng và sản phẩm a khách đã đặt để suy ra được sản phẩm còn trong kho => số lượng tồn kho
                foreach ($quantily_order as $item) {
                    //Từ sản phẩm a khách đặt suy ra được sản phẩm còn trong kho và số lượng tồn kho là bao nhiêu
                    $num_product = Product::find($item->product_id)->quantily;
                    //Cập nhập lại số lượng sản phẩm tồn kho = số lượng tồn kho + số lượng khách đã đặt = (giữ nguyên số lượng cũ)
                    Product::where('id', $item->product_id)->update(['quantily' => $num_product - $item->quantily]);
                }
            }
            Order::where('order_code', $order_code)->update(['status' => $action]);
            //Nếu thao tác chúng ta muốn là hủy đơn thì thực hiện xóa tạm đơn hàng theo mã ra khỏi bảng đơn hàng
            if ($action == "Hủy đơn") {
                Order::where('order_code', $order_code)->delete();
            }
            //Sau khi thao tác làm thay đổi số lượng, khi đặt hàng thành công hoặc hủy đơn thì cập nhập lại trạng thái theo thao tác đó
            //Và thực hiện chuyển hướng
            return redirect('admin/orders/list')->with('order', 'Cập nhật trạng thái cho đơn hàng ' . $order_code . ' thành công');
            //Ngược lại nếu action không tồn tại giá trị tức là chưa thực thi thao tác thì chuyển hướng load view chi tiết đơn hàng kèm thông báo
        } else {
            //Chỉ có cách này mới lấy được id theo chi tiết đơn hàng nếu trỏ đường dẫn thì ko có được $id
            return redirect()->route('admin.order.detail', $id)->with('status', 'Bạn chưa chọn trạng thái muốn thay đổi');
        }
    }
    //=================Danh sách khách hàng=================//

    //Phương thức hiển thị danh sách khách hàng
    function listCustomer(Request $request)
    {
        //Lấy giá trị status ở url
        $status = $request->input('status');
        //Thao tác chọn
        $list_act = [
            'delete' => 'Xóa vĩnh viễn',
        ];
        //Nếu url status có trạng thái là thùng rác thì xuất ra danh sách trong thùng rác kèm theo thao tác action
        if ($status == 'trash') {
            $list_act = [
                'restore' => 'Khôi phục lại',
                'forceDelete' => 'Xóa vĩnh viễn'
            ];
            $customers = Customer::onlyTrashed()->paginate(10);
        //Ngược lại nếu ko có trạng thái chưa xóa tạm nếu search trống ko có từ khóa thì hiển thị dữ liệu chưa xóa tạm
        } else {
            $keyword = "";
            if ($request->input('keyword')) {
                $keyword = $request->input('keyword');
            }
            $customers = Customer::where('fullname', 'LIKE', "%{$keyword}%")->paginate(10);
        }
        //Lấy tổng số bảng ghi đã có kể cả  đã xóa tạm thời ở database
        $count_customer_active = Customer::count();
        //Lấy tổng xóa bảng ghi đã bị xóa tạm thời ở database
        $count_customer_trash = Customer::onlyTrashed()->count();
        //Tổng số dữ liệu của hai phần đã lấy ở trên $count[0, 1]
        $count = [$count_customer_active, $count_customer_trash];
        //Trả lại cho view với những dữ liệu trên để hiển thị
        return view('admin.orders.customer', compact('status', 'list_act', 'customers', 'count'));
    }

    //Phương thức xóa tạm khách hàng đó
    function disable($id)
    {
        //Lấy khách hàng định xóa theo id kiểm tra nếu đang trong tình trạng hoạt động thì
        $customer = Customer::withTrashed()->where('id', $id)->get();
        foreach ($customer as $value) {
            if ($value->status_customer == "Hoạt động") {
                //xóa tạm bên ngoài và cập nhập lại trong thùng rác thành vô hiệu hóa
                Customer::withoutTrashed()->where('id', $id)
                    ->delete();
                Customer::onlyTrashed()->where('id', $id)
                    ->update(['status_customer' => 'Vô hiệu hóa']);
            }
            break;
        }
        return redirect('admin/orders/customer/list')->with('status', 'Vô hiệu hóa thành công khách hàng');
    }

    //Phương thức khôi phục khách hàng bị vô hiệu hóa
    function restore($id)
    {
        //Lấy khách hàng định xóa theo id kiểm tra nếu đang trong tình trạng vô hiệu hóa thì
        $customer = Customer::withTrashed()->where('id', $id)->get();
        // return $customer;
        foreach ($customer as $value) {
            if ($value->status_customer == "Vô hiệu hóa") {
                //khôi phục trong thùng rác và cập nhập lại bên ngoài thành hoạt động
                Customer::onlyTrashed()->where('id', $id)
                    ->restore();
                Customer::withoutTrashed()->where('id', $id)
                    ->update(['status_customer' => 'Hoạt động']);
            }
            break;
        }
        //Chuyển hướng khi xóa xong kèm thông báo
        return redirect('admin/orders/customer/list')->with('status', 'Khôi phục lại khách hàng thành công');
    }

    //Phương thức xóa vĩnh viễn khách hàng đó
    function deleteCustomer($id)
    {
        //Từ id của khách hàng suy ra được customer_id đó có đơn hàng
        $list_order = Order::where('customer_id','=', $id)->get();
        //return $list_order;
        //TH1: Nếu đơn hàng tồn tại tức là khách hàng đang mua thì hàng thì ko cho xóa
        if ($list_order->count() > 0) {
            return redirect('admin/orders/customer/list')->with('status', 'Không thể xóa khách hàng này vì khách hàng này đang trong quá trình hoạt đông đặt hàng của hệ thống');
        //TH2: Nếu khách hàng không có đặt đơn hàng nào cho phép xóa
        } else {
            //Chuyển hướng cho xóa khi khách hàng không đặt hàng
            Customer::where('id', $id)->forceDelete();
            return redirect('admin/orders/customer/list')->with('status', 'Xóa vĩnh viễn khách hàng thành công');
        }
    }

    //Phương thức thực hiện tác vụ check
    function actionCustomer(Request $request)
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
                    //Xía danh sách check theo điều kiện id đó đã xác thực và đang hoạt động
                    Customer::withoutTrashed()
                        ->whereIn('id', $list_check)
                        ->delete();
                    Customer::onlyTrashed()
                        ->whereIn('id', $list_check)
                        ->update(['status_customer' => 'Vô hiệu hóa']);
                    //Thực hiện chuyển hướng về danh sách trang kèm trạng thái thành công
                    return redirect('admin/orders/customer/list')->with('status', 'Vô hiệu hóa thành viên thành công');
                }
                //Nếu tác vụ này là khôi phục thì cho phép khôi phục
                if ($act == 'restore') {
                    Customer::onlyTrashed()
                        ->whereIn('id', $list_check)
                        ->restore();
                    Customer::withoutTrashed()
                        ->whereIn('id', $list_check)
                        ->update(['status_customer' => 'Hoạt động']);
                    //Thực hiện chuyển hướng khi thành công chọn thao tác khôi phục
                    return redirect('admin/orders/customer/list')->with('status', 'Bạn đã khôi phục thành viên thành công');
                }
                //Nếu tác vụ này là xóa vĩnh viễn thì cho phép xóa vĩnh viễn
                if ($act == 'forceDelete') {
                    //Tìm table products với điều kiện id đó có trong danh sách check và lấy ra
                    Customer::withTrashed()
                        ->whereIn('id', $list_check)
                        ->forceDelete();
                    //Thực hiện chuyển hướng khi xóa vĩnh viễn thành công
                    return redirect('admin/orders/customer/list')->with('status', 'Bạn đã xõa vĩnh viễn thành viên thành công');
                }
            }
            //Nếu trong danh sách check trống (không có tác vụ) thì chuyển hướng ra dach sách trang hiện có và hiển thị thông báo
            return redirect('admin/orders/customer/list')->with('status', 'Bạn phải chọn hình thức vô hiệu hóa, xóa vĩnh viễn hoặc kích hoạt lại');
        } else {
            //Nếu trong danh sách check trống (không có tác vụ) thì chuyển hướng ra dach sách trang hiện có và hiển thị thông báo
            return redirect('admin/orders/customer/list')->with('status', 'Bạn cần chọn thao tác cần thực thi');
        }
    }
}
