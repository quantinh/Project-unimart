<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendInfoOrder extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    //Tạo thuộc tính data nhận dữ liệu từ controller truyền qua
    public $data;

    //Phương thức khỏi tạo đầu tiên lấy dữ liệu gọi dến
    public function __construct($data)
    {
        //Nạp dữ liệu cho thuộc tính data
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.send-info-order')
        //Tên và email người gửi
        ->from('htinh7444@gmail', 'Unimart')
        //Tiêu đề thư gửi
        ->subject('[Unimart] Thông báo đặt hàng thành công')
        //Tiêu đề và nội dung trong thư gửi qua view with có thể dạng mảng gửi nhiều dữ liệu ->with(['', '', '']);
        ->with($this->data);
    }
}
