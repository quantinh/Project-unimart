<?php

namespace App\Components;

class Recusive {
    private $data;
    private $htmlSlelect = '';

    //Phương thức khởi tạo
    public function __construct($data)
    {
        $this->data = $data;

    }

    //Phương thức danh mục đệ quy menu
    public function categoryRecusive($parentId, $id = 0, $text = '')
    {
        foreach ($this->data as $value) {
            if ($value['parent_id'] == $id) {
                if ( !empty($parentId) && $parentId == $value['id']) {
                    $this->htmlSlelect .= "<option selected value='" . $value['id'] . "'>" . $text . $value['name_menu'] . "</option>";
                } else {
                    $this->htmlSlelect .= "<option value='" . $value['id'] . "'>" . $text . $value['name_menu'] . "</option>";
                }
                $this->categoryRecusive($parentId, $value['id'], $text. '--');
            }
        }
        return $this->htmlSlelect;
    }
}
