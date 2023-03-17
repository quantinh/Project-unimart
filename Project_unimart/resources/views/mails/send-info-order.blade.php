<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#dcf0f8"
    style="margin:0;padding:0;background-color:#f2f2f2;width:100%!important;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px">
    <tbody>
        <tr>
            <td align="center" valign="top"
                style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal">
                <table border="0" cellpadding="0" cellspacing="0" width="600" style="margin-top:15px; margin-bottom: 15px;">
                    <tbody>
                        <tr>
                            <td align="center" valign="bottom"
                                id="m_-3595239146092275950m_186734553970362782headerImage">
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                            <td valign="top" bgcolor="#FFFFFF" width="100%" style="padding:0">
                                                <a style="border:medium none;text-decoration:none;color:#007ed3"
                                                    href="https://x64km.mjt.lu/lnk/AVoAAAasXUEAAAAAAAAAACMP60oAAAAA3NwAAAAAABPsQgBg8368bghVz0YPQz2O3Ns0S_V4xwAPBhU/1/LuVFfBpYhsfmIpUWW0Z3qA/aHR0cDovL3Rpa2kudm4vP3V0bV9zb3VyY2U9dHJhbnNhY3Rpb25hbCtlbWFpbCZ1dG1fbWVkaXVtPWVtYWlsJnV0bV90ZXJtPWxvZ28mdXRtX2NhbXBhaWduPW5ldytvcmRlcg"
                                                    target="_blank"
                                                    data-saferedirecturl="https://www.google.com/url?q=https://x64km.mjt.lu/lnk/AVoAAAasXUEAAAAAAAAAACMP60oAAAAA3NwAAAAAABPsQgBg8368bghVz0YPQz2O3Ns0S_V4xwAPBhU/1/LuVFfBpYhsfmIpUWW0Z3qA/aHR0cDovL3Rpa2kudm4vP3V0bV9zb3VyY2U9dHJhbnNhY3Rpb25hbCtlbWFpbCZ1dG1fbWVkaXVtPWVtYWlsJnV0bV90ZXJtPWxvZ28mdXRtX2NhbXBhaWduPW5ldytvcmRlcg&amp;source=gmail&amp;ust=1645971112612000&amp;usg=AOvVaw3xHDjUReKMSvF-CtDTyCAv">
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr style="background:#fff">
                            <td align="left" width="600" height="auto" style="padding:15px">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>
                                                {{-- Đổ dữ liệu thông tin khách hàng  --}}
                                                @foreach($info_customer as $info)
                                                    <h1
                                                        style="font-size:14px;font-weight:bold;color:#444;padding:0 0 5px 0;margin:0">
                                                        Chào {{ $info->fullname }}. Đơn hàng của bạn đã đặt thành
                                                        công!</h1>
                                                    <p
                                                        style="margin:4px 0;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal">
                                                        Chúng tôi đang chuẩn bị hàng để bàn giao cho đơn vị vận chuyển
                                                    </p>
                                                    <h3
                                                        style="font-size:13px;font-weight:bold;color: red; text-transform:uppercase;margin:20px 0 0 0;border-bottom:1px solid #ddd">
                                                        Đơn hàng ngày: {{ $info->created_at->format('H:i d-m-Y') }}
                                                    </h3>
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr>
                                        </tr>
                                        <tr>
                                            <td
                                                style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px">
                                                <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th align="left" width="50%"
                                                                style="padding:6px 9px 0px 9px;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;font-weight:bold">
                                                                Thông tin khách hàng</th>
                                                            <th align="left" width="50%"
                                                                style="padding:6px 9px 0px 9px;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;font-weight:bold">
                                                                Địa chỉ giao hàng</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        {{-- Đổ dữ liêu thông tin khách hàng --}}
                                                        @foreach ($info_customer as $info)
                                                            <tr>
                                                                <td valign="top"
                                                                    style="padding:3px 9px 9px 9px;border-top:0;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal">
                                                                    <span
                                                                        style="text-transform:capitalize"><strong>Họ và tên:</strong> {{ $info->fullname }}</span><br>
                                                                    <a href="" target="_blank"><strong>Email:</strong> {{ $info->email }}</a><br>
                                                                    <strong>Số điện thoại:</strong> {{ $info->phone }}
                                                                </td>
                                                                <td valign="top"
                                                                    style="padding:3px 9px 9px 9px;border-top:0;border-left:0;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal">
                                                                    {{ $info->address }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <table
                                                    style="width:100%;border-collapse:collapse;border-spacing:2px;background:#f5f5f5;display:table;box-sizing:border-box;border:0;border-color:grey">
                                                    <thead
                                                        style="display:table-header-group;vertical-align:middle;border-color:inherit">
                                                        <tr>
                                                            <th
                                                                style="text-align:left;background-color:rgb(35,40,45);padding:6px 9px;color: #ffce3e;text-transform:uppercase;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:14px">
                                                                Tên sản phẩm
                                                            </th>
                                                            <th
                                                                style="text-align:left;background-color:rgb(35,40,45);padding:6px 9px;color: #ffce3e;text-transform:uppercase;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:14px">
                                                                Giá
                                                            </th>
                                                            <th
                                                                style="text-align:left;background-color:rgb(35,40,45);padding:6px 9px;color: #ffce3e;text-transform:uppercase;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:14px">
                                                                Số lượng
                                                            </th>
                                                            <th
                                                                style="text-align:left;background-color:rgb(35,40,45);padding:6px 9px;color: #ffce3e;text-transform:uppercase;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:14px">
                                                                Thành tiền
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody
                                                        style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;background-color:rgb(238,238,238);display:table-row-group;vertical-align:middle;border-color:inherit">
                                                        @foreach ($detail_order as $order)
                                                            <tr>
                                                                <td style="padding:3px 9px">
                                                                    <strong>{{ $order->name_product }}</strong>
                                                                </td>
                                                                <td style="padding:3px 9px">
                                                                    {{ number_format($order->price, 0,',','.') }}đ
                                                                </td>
                                                                <td style="padding:3px 9px">
                                                                    {{ $order->quantily }}
                                                                </td>
                                                                <td style="padding:3px 9px">
                                                                    {{ number_format($order->sub_total, 0,',','.') }}đ
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        <tr>
                                                            <td colspan="3"
                                                                style="text-align:left;background-color:rgb(238,238,238);padding:6px 9px;color:rgb(0, 0, 0);text-transform:uppercase;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:14px">
                                                                <strong>Tổng thanh toán:</strong>
                                                            </td>
                                                            <td colspan="1" style="padding:3px 9px;"><span style="color:red">
                                                                <strong>{{ number_format($total_order, 0,',','.') }}Đ</strong></span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <br>
                                                <p
                                                    style="margin:0;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal">
                                                    Quý khách vui lòng giữ lại hóa đơn, hộp sản phẩm và phiếu bảo hành (nếu có) để đổi trả hàng hoặc bảo hành khi cần thiết.
                                                </p>
                                                <p
                                                    style="margin:10px 0 0 0;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;line-height:18px;font-weight:normal">
                                                    Liên hệ Hotline
                                                    <strong style="color:#007ed3">0377.953.849</strong>
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <br>
                                                <p
                                                    style="font-family:Arial,Helvetica,sans-serif;font-size:12px;margin:0;padding:0;line-height:18px;color:#444;font-weight:bold">
                                                    <span>Unimart</span> cảm ơn quý khách đã đặt hàng trên hệ thống !<br>
                                                </p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
