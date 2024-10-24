<footer class="style2">
    <div class="container">
        <div class="row pt-80 pb-80 justify-content-center">
            <div class="col-lg-5 col-md-12">
                <div class="footer-widget">
                    <div class="footer-icon">
                        <img src="{{ asset('assets/clients/images/logo/logo.png') }}" style="width:100px" alt="Logo">
                    </div>
                    <div class="widget-title">
                        <h2>{{ env('APP_NAME') }} <br><span>hân hạnh phục vụ bạn</span></h2>
                    </div>
                    <div class="footer-btn">
                        <a class="primary-btn6" href="{{ route('product') }}">Xem sản phẩm</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-sm-6">
                <div class="footer-widget one">
                    <div class="widget-title">
                        <h3>Dịch vụ</h3>
                    </div>
                    <div class="menu-container">
                        <ul>
                            <li><a href="about.html">Cắt tỉa thú cưng</a></li>
                            <li><a href="shop.html">Grooming</a></li>
                            <li><a href="#">Lưu trú</a></li>
                            <li><a href="#">SPA</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-sm-6">
                <div class="footer-widget one ">
                    <div class="widget-title">
                        <h3>Tài khoản</h3>
                    </div>
                    <div class="menu-container">
                        <ul>
                            <li><a href="{{ route('account') }}">Trang cá nhân</a></li>
                            <li><a href="#">Danh sách yêu thích</a></li>
                            <li><a href="{{ route('account') }}">Tra cứu đơn hàng</a></li>
                            <li><a href="{{ route('cart') }}">Giỏ hàng</a></li>
                            <li><a href="{{ route('account') }}#don-hang">Danh sách hoá đơn</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="footer-widget one mb-0">
                    <div class="widget-title">
                        <h3>Kết nối với chúng tôi</h3>
                    </div>
                    <div class="download-link">
                        <div id="fb-root"></div>
                        <script async defer crossorigin="anonymous" src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v20.0">
                        </script>
                        <div class="fb-page" data-href="https://www.facebook.com/facebook" data-tabs="timeline"
                            data-width="" data-height="70" data-small-header="false" data-adapt-container-width="true"
                            data-hide-cover="false" data-show-facepile="false">
                            <blockquote cite="https://www.facebook.com/facebook" class="fb-xfbml-parse-ignore"><a
                                    href="https://www.facebook.com/facebook">Facebook</a></blockquote>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row border-top align-items-center">
            <div class="col-lg-6">
                <div class="copyright-area">
                    <p>© {{ '2024 ' . env('APP_NAME') }} </p>
                </div>
            </div>
            <div class="col-lg-6 d-flex justify-content-md-end justify-content-center">
                <div class="social-area">
                    <ul>
                        @if (getOption('DB_SOCIAL_LINK_FACEBOOK'))
                            <li><a href="{{ getOption('DB_SOCIAL_LINK_FACEBOOK') }}" target="_blank"><i
                                        class="bx bxl-facebook"></i></a></li>
                        @endif

                        @if (getOption('DB_SOCIAL_LINK_TIKTOK'))
                            <li><a href="{{ getOption('DB_SOCIAL_LINK_TIKTOK') }}" target="_blank"><i
                                        class="bx bxl-tiktok"></i></a></li>
                        @endif

                        @if (getOption('DB_SOCIAL_LINK_YOUTUBE'))
                            <li><a href="{{ getOption('DB_SOCIAL_LINK_YOUTUBE') }}" target="_blank"><i
                                        class="bx bxl-youtube"></i></a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
