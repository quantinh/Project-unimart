<footer class="footer">
    <div class="grid wide footer-content">
        <div class="row">
            <div class="col l-3 me-4 c-12">
                <h3 class="footer-heading">Chăm sóc khách hàng</h3>
                <ul class="footer-list">
                    <li class="footer-item">
                        <a href="" class="footer-item-link">Trung tâm trợ giúp</a>
                    </li>
                    <li class="footer-item">
                        <a href="" class="footer-item-link">Unimart Store</a>
                    </li>
                    <li class="footer-item">
                        <a href="" class="footer-item-link">Hướng dẫn mua hàng</a>
                    </li>
                </ul>
            </div>
            <div class="col l-3 me-4 c-12">
                <h3 class="footer-heading">Chính sách chung</h3>
                <ul class="footer-list">
                    <li class="footer-item">
                        <a href="" class="footer-item-link">Chính sách vận chuyển</a>
                    </li>
                    <li class="footer-item">
                        <a href="" class="footer-item-link">Chính sách bảo hành</a>
                    </li>
                    <li class="footer-item">
                        <a href="" class="footer-item-link">Chính sách đổi trả</a>
                    </li>
                </ul>
            </div>
            <div class="col l-3 me-4 c-12">
                <h3 class="footer-heading">Liên kết mạng xã hội</h3>
                <ul class="footer-list">
                    <li class="footer-item">
                        {{-- lấy giá trị link cấu hình từ admin  --}}
                        {!! getConfigValueFromSettingTable('link_contact_facebook') !!}
                    </li>
                    <li class="footer-item">
                        {{-- lấy giá trị link cấu hình từ admin  --}}
                        {!! getConfigValueFromSettingTable('link_contact_intargram') !!}
                    </li>
                    <li class="footer-item">
                        {{-- lấy giá trị link cấu hình từ admin  --}}
                        {!! getConfigValueFromSettingTable('link_contact_linkedin') !!}
                    </li>
                </ul>
            </div>
            <div class="col l-3 me-4 c-12">
                <h3 class="footer-heading mb-4">Chấp nhận thanh toán</h3>
                <div class="footer-download">
                    <img src="{{ asset('images/icons/qr-code.png') }}" alt="Download QR" class="footer-download-qr" />
                    <div class="footer-download-apps">
                        <a href="" class="footer-download-apps-link">
                            <img src="{{ asset('images/icons/cb.png') }}" alt="gallery"
                                class="footer-download-app-img">
                        </a>
                        <a href="" class="footer-download-apps-link">
                            <img src="{{ asset('images/icons/paymen.png') }}" alt="google store"
                                class="footer-download-app-img">
                        </a>
                        <a href="" class="footer-download-apps-link">
                            <img src="{{ asset('images/icons/vn.png') }}" alt="google store"
                                class="footer-download-app-img">
                        </a>
                        <a href="" class="footer-download-apps-link">
                            <img src="{{ asset('images/icons/pay.png') }}" alt="google store"
                                class="footer-download-app-img">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="grid wide">
            {{-- lấy giá trị link cấu hình từ admin  --}}
            {!! getConfigValueFromSettingTable('footer_copyrights') !!}
        </div>
    </div>
</footer>
