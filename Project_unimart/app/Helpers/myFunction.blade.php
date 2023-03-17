<?php

//Hàm đệ quy hiển thị danh mục sản phẩm
function showOptionProductCat($categorys, $parent_id = 0, $char = '')
{
    foreach ($categorys as $key => $item) {
        // Nếu là chuyên mục con thì hiển thị
        if ($item->parent_id == $parent_id) {
            // Xử lý hiển thị chuyên mục
            echo '<option value="' . $item->id . '">' . $char . $item->cat_name . '</option>';
            // Xóa chuyên mục đã lặp
            unset($categorys[$key]);
            // Tiếp tục đệ quy để tìm chuyên mục con của chuyên mục đang lặp
            showOptionProductCat($categorys, $item->id, $char . ' -- ');
        }
    }
}

//Hàm đệ quy hiểm thị bảng tên danh mục
function showProductCatTable($list_cats, $parent_id = 0, $char = '')
{
    foreach ($list_cats as $key => $value) {
        // Nếu là chuyên mục con thì hiển thị
        if ($value->parent_id == $parent_id) {
            $status = '<span class="badge badge-success d-inline-block">Công khai</span>';
            if ($value->deleted_at != null) {
                $status = '<span class="badge badge-warning d-inline-block">Chờ duyệt</span>';
            }
            echo '<tr>
                    <td>' . $char . $value->cat_name . '</td>
                    <td>' . $value->slug . '</td>
                    <td class="text-center"></td>
                    <td>' . $status . '</td>
                    <td class="text-info">' . $value->name . '</td>
                    <td>' . $value->created_at . '</td>
                    <td>
                        <a href="' . route('admin.product.cat.change', $value->id) . '"
                            class="btn btn-dark btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top"
                            title="Thay đổi trạng thái">
                            <i class="fas fa-sync"></i>
                        </a>
                        <a href="' . route('admin.product.cat.edit', $value->id) . '"
                            class="btn btn-success btn-sm rounded-0" type="button"
                            data-toggle="tooltip" data-placement="top"
                            title="Chỉnh sửa danh mục">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a href="' . route('admin.product.cat.delete', $value->id) .'"
                            onclick="return confirm(\'Bạn có chắc chắn xóa danh mục sản phẩm này không ?\')"
                            class="btn btn-danger btn-sm rounded-0" type="button"
                            data-toggle="tooltip" data-placement="top" title="Xóa vĩnh viễn">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>';
            //Xóa chuyên mục đã lặp
            unset($list_cats[$key]);
            //Tiếp tục đệ quy để tìm chuyên mục con của chuyên mục đang lặp
            showProductCatTable($list_cats, $value->id, $char . ' -- ');
        }
    }
}

//Hàm upload ảnh
function upload($field_name)
{
    request()->validate([
        $field_name => 'required|mines:jpeg,png,gif',
    ]);
    $info = pathinfo(request()->$filed_name->getClientOriginalName());
    $file_name = $info['filename'];
    $file_ex = $info['extension'];
    $full_name = time() . '.' . Str::slug($file_name) . '.' . $file_ex;
    request()->$field_name->move(public_path('uploads'), $file_name);
    return $file_name;
}

//C2: Lỗi ko xóa được được khi cha có con chỉ xóa cha và con thì vẫn còn trong cơ sở dữ liệu tối ưu hơn các cách khác là có thể sử dụng nhiều nơi (đang trong quá trình phát triển thêm)
function data_tree($data, $parent_id = 0, $level = 0)
{
    //Tạo mảng rỗng để chưa các danh mục khi duyệt lấy ra
    $result = [];
    //Duyệt lặp qua từng phần tử
    foreach ($data as $item) {
        //Nếu phần tử đó có key 'parent_id' = 0 tức là bằng giá trị parent_id mặc định là 0 thì cho nó vào mảng khởi tạo
        if ($item->parent_id == $parent_id) {
            //Thêm 'key' giá trị cho tham số level
            $item->level = $level;
            $result[] = $item;
            //Khi xét tiếp phần tử tiếp theo thì xóa phần tử đã xét ở mảng data truyền vào lúc đầu để tránh sự trùng lặp khi xét tiếp theo
            unset($data[$item->id]);
            //Khi tìm ra thằng cha đưa vào mảng thì chưa vội thêm phần tử cha tiếp theo mà tìm thêm thằng con của nó để nạp vào dùng đó để đưa vào mảng dữ liệu và dữ liệu được lặp ở trên qua từng phần tử có giá trị id cha bằng parent_id
            $child = data_tree($data, $item->id, $level + 1); // Cấp bậc thằng con + thằng trên nó là 1
            //Sau khi tìm được thì gộp thằng cha chung với thằng con trong một mảng tham số sẽ là mảng cha trc đó , mảng con chú ý khi gộp thì đều bằng nhau hết ko có mảng trong lồng con
            $result = array_merge($result, $child);
        }
    }
    return $result;
}

//Hàm hiển thị giá trị thiết lập link của admin
function getConfigValueFromSettingTable($configKey)
{
    //Lấy các giá trị theo config_key lấy đầu 1 phần tử
    $setting = \App\Setting::where('config_key', $configKey)->first();
    //Kiểm tra nếu có giá trị thì lấy giá trị hiển thị
    if (!empty($setting)) {
        return $setting->config_value;
    }
    //Nếu không có giá trị thì mặc định rỗng
    return null;
}
?>
