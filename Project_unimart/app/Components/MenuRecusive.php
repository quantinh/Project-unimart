<?php

namespace App\Components;

use App\Menu;

//Lớp menu đệ quy
class MenuRecusive {
    private $html;

    //Phương thức khởi tạo in ra chuỗi json html cho đệ quy
    public function __construct()
    {
        $this->html = '';
    }

    //Phương thức thêm chạy đệ quy menu
    public function menuRecusiveAdd($parentId = 0, $subMark = '')
    {
        //Lấy ra menu cha có parent_id = 0; lúc này đầu vào tham số cũng bằng = 0
        $data = Menu::where('parent_id', $parentId)->get();
        foreach ($data as $dataItem) {
            //Duyệt qua cha lấy cha ra và in ra trên option
            $this->html .= '<option value="' . $dataItem->id .'">' . $subMark . $dataItem->name_menu . '</option>';
            //Tiếp túc nó chạy hàm menuRecusiveAdd() từ hàm đó nó suy ra xem thằng nào có parent_id bằng id nó lặp ko rồi chạy hàm ở trên tiếp vòng lặp thứ 2, rồi 3 khi không tìm thấy thì kết thúc
            $this->menuRecusiveAdd($dataItem->id, $subMark . '--');
        }
        return $this->html;
    }

    //Phương thức chỉnh sửa chạy đệ quy menu
    public function menuRecusiveEdit($parentIdMenuEdit, $parentId = 0, $subMark = '')
    {
        $data = Menu::where('parent_id', $parentId)->get();
        foreach ($data as $dataItem) {
            //Khi parent_id chỉnh sửa bằng với phần tử duyệt thì in ra json select
            if($parentIdMenuEdit == $dataItem->id) {
                $this->html .= '<option selected value="' . $dataItem->id .'">' . $subMark . $dataItem->name_menu . '</option>';
            } else {
                $this->html .= '<option value="' . $dataItem->id .'">' . $subMark . $dataItem->name_menu . '</option>';
            }
            $this->menuRecusiveEdit($parentIdMenuEdit, $dataItem->id, $subMark . '--');
        }
        return $this->html;
    }

    //Phương thức list chạy đệ quy menu (đang trong quá trình phát triển thêm)
    public function menuRecusiveList($parentId = 0, $subMark = '')
    {
        //Lấy ra menu cha có parent_id = 0; lúc này đầu vào tham số cũng bằng = 0
        $data = Menu::where('parent_id', $parentId)->get();
        foreach ($data as $dataItem) {
            //Duyệt qua cha lấy cha ra và in ra trên option
            $this->html .=
            '<tr>
                <td class="text-center">' .  $dataItem->id . '</td>
                <td class="text-center">' .  $subMark . $dataItem->name_menu . '</td>
                <td class="text-center">' . $dataItem->slug . '</td>
                <td class="text-center">' . $dataItem->status . '</td>
                <td class="text-info">
                    <span class="text-info">' . $dataItem->name . '</span>
                </td>
                <td class="text-center">' .
                    $dataItem->created_at->format('H:i d-m-Y') .
                '</td>
                <td class="text-center">
                    <a href="' . route('admin.menu.changeStatus', $dataItem->id) . '"
                        class="btn btn-dark btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top"
                        title="Thay đổi trạng thái">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                    <a href="' . route('admin.menu.edit', ['id' => $dataItem->id]) . '?type=' . $dataItem->type . '"
                        class="btn btn-success btn-sm rounded-0" type="button"
                        data-toggle="tooltip" data-placement="top"
                        title="Chỉnh sửa menu">
                        <i class="fa fa-edit"></i>
                    </a>
                    <a href="' . route('admin.menu.delete', $dataItem->id) .'"
                        onclick="return confirm(\'Bạn có chắc chắn xóa menu này không ?\')"
                        class="btn btn-danger btn-sm rounded-0" type="button"
                        data-toggle="tooltip" data-placement="top" title="Xóa vĩnh viễn">
                        <i class="fa fa-trash"></i>
                    </a>
                </td>
            </tr>';
            // '<option value="' . $dataItem->id .'">' . $subMark . $dataItem->name_menu . '</option>';
            //Tiếp túc nó chạy hàm menuRecusiveAdd() từ hàm đó nó suy ra xem thằng nào có parent_id bằng id nó lặp ko rồi chạy hàm ở trên tiếp vòng lặp thứ 2, rồi 3 khi không tìm thấy thì kết thúc
            $this->menuRecusiveAdd($dataItem->id, $subMark . '--');
        }
        return $this->html;
    }
}
