<div class="top-bar two">
    <div class="container-lg container-fluid ">
        <div class="row">
            <div class="col-lg-12 d-flex align-items-center justify-content-md-between justify-content-center">
                <div class="contact-number">
                    <a href="tel:{{ getOption('DB_PHONE') }}"><img
                            src="{{ asset('assets/clients/images/icon/support2.svg') }}" alt>
                        {{ getOption('DB_PHONE') }}</a>
                </div>
                @if (getOption('DB_SHORT_STANDOUT_NOTIFICATION'))
                    <div class="opening-time text-center">
                        <p>{{ getOption('DB_SHORT_STANDOUT_NOTIFICATION') }}</p>
                    </div>
                @endif
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
</div>


<header class="header-area style-3">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="header-logo">
            <a href="{{ route('home') }}"><img alt="image" class="img-fluid" style="width:100px"
                    src="{{ getLogo() }}"></a>
        </div>
        <div class="main-menu">
            <div class="mobile-logo-area d-lg-none d-flex justify-content-between align-items-center">
                <div class="mobile-logo-wrap">
                    <a href="{{ route('home') }}"><img alt="image" style="width:100px"
                            src="{{ getLogo() }}"></a>
                </div>
                <div class="menu-close-btn">
                    <i class="bi bi-x-lg"></i>
                </div>
            </div>
            <ul class="menu-list">
                @foreach (getMenu() as $menu)
                    <li
                        class="{{ $menu->url == 'services' || ($menu->children && $menu->children->isNotEmpty()) ? 'menu-item-has-children' : '' }}
                        {{ request()->is($menu->url) ? 'active' : '' }}">
                        <a
                            href="{{ $menu->url == 'services' || ($menu->children && $menu->children->isNotEmpty()) ? '' : $menu->url }}">{{ $menu->text }}</a>
                        @if ($menu->children && $menu->children->isNotEmpty())
                            <!-- Kiểm tra xem có con không -->
                            <ul class="sub-menu">
                                @foreach ($menu->children as $child)
                                    <!-- Lặp qua các mục con -->
                                    <li class="{{ request()->is($child->url) ? 'active' : '' }}">
                                        <a href="{{ $child->url }}">{{ $child->text }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        @if ($menu->url == 'services')
                            <ul class="sub-menu">
                                @if (getService() && getService()->isNotEmpty())
                                    @foreach (getService() as $service)
                                        <li>
                                            <a
                                                href="{{ route('service', ['slug' => $service->slug]) }}">{{ $service->name }}</a>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
            <div class="for-mobile-menu d-lg-none d-block">
                <div class="hotline mb-5">
                    <div class="hotline-info">
                        <span>Liên hệ</span>
                        <h6><a href="tel:{{ getOption('DB_PHONE') }}">{{ getOption('DB_PHONE') }}</a></h6>
                    </div>
                </div>
                <ul class="social-link mb-5">
                    <li><a href="">
                            <svg width="14" height="13" viewBox="0 0 14 13" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M12.4147 1.51371C11.0037 0.302997 8.92573 0.534835 7.61736 1.87434L7.12993 2.38954L6.61684 1.87434C5.33413 0.534835 3.23047 0.302997 1.81948 1.51371C0.203258 2.90473 0.126295 5.37767 1.56294 6.87174L6.53988 12.0237C6.84773 12.3586 7.38647 12.3586 7.69433 12.0237L12.6713 6.87174C14.1079 5.37767 14.0309 2.90473 12.4147 1.51371Z" />
                            </svg>
                        </a></li>
                    <li><a href="{{ route('cart') }}">
                            <svg width="16" height="13" viewBox="0 0 16 13" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M15.6365 5.46266C15.6365 5.12721 15.3541 4.84336 15.0202 4.84336H13.274L10.5262 1.07601C10.2694 0.688956 9.75576 0.611544 9.39624 0.895386C9.01104 1.15342 8.934 1.6695 9.21648 2.03075L11.2452 4.84336H5.21036L7.2391 2.03075C7.52158 1.6695 7.44454 1.15342 7.05934 0.895386C6.69982 0.611544 6.18621 0.688956 5.92941 1.07601L3.18163 4.84336H1.46105C1.10153 4.84336 0.844727 5.12721 0.844727 5.46266V5.87552C0.844727 6.23677 1.10153 6.49481 1.46105 6.49481H1.66649L2.33418 11.2169C2.41122 11.8362 2.92482 12.2749 3.54115 12.2749H12.9144C13.5308 12.2749 14.0444 11.8362 14.1214 11.2169L14.8148 6.49481H15.0202C15.3541 6.49481 15.6365 6.23677 15.6365 5.87552V5.46266ZM8.85696 10.0041C8.85696 10.3654 8.57447 10.6234 8.24063 10.6234C7.88111 10.6234 7.6243 10.3654 7.6243 10.0041V7.1141C7.6243 6.77865 7.88111 6.49481 8.24063 6.49481C8.57447 6.49481 8.85696 6.77865 8.85696 7.1141V10.0041ZM11.7331 10.0041C11.7331 10.3654 11.4507 10.6234 11.1168 10.6234C10.7573 10.6234 10.5005 10.3654 10.5005 10.0041V7.1141C10.5005 6.77865 10.7573 6.49481 11.1168 6.49481C11.4507 6.49481 11.7331 6.77865 11.7331 7.1141V10.0041ZM5.98077 10.0041C5.98077 10.3654 5.69829 10.6234 5.36445 10.6234C5.00492 10.6234 4.74812 10.3654 4.74812 10.0041V7.1141C4.74812 6.77865 5.00492 6.49481 5.36445 6.49481C5.69829 6.49481 5.98077 6.77865 5.98077 7.1141V10.0041Z" />
                            </svg>
                        </a></li>
                    @auth
                        <li><a href="{{ route('account') }}">
                                <svg width="15" height="15" viewBox="0 0 15 15" xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_1585_341)">
                                        <path
                                            d="M6.98716 0.938832C6.28609 1.04711 5.65949 1.38227 5.169 1.90563C4.62972 2.48055 4.3498 3.14571 4.31128 3.94235C4.25735 5.0561 4.80177 6.12086 5.74167 6.73703C6.20391 7.04125 6.64818 7.19594 7.18747 7.23977C8.18643 7.31711 9.03901 7.00258 9.72724 6.29875C10.2742 5.74188 10.5516 5.13344 10.6183 4.35743C10.7108 3.32102 10.3205 2.3568 9.54234 1.68133C9.03901 1.24821 8.57676 1.03164 7.93733 0.938832C7.62916 0.895004 7.26964 0.892426 6.98716 0.938832Z" />
                                        <path
                                            d="M4.65531 7.29655C3.49456 7.4203 2.68821 8.25561 2.31327 9.7303C2.06418 10.7126 1.99998 11.8933 2.15919 12.5405C2.29016 13.0587 2.71902 13.5846 3.21465 13.8373C3.43807 13.9507 3.75907 14.0435 4.02871 14.0744C4.18793 14.0951 5.40004 14.1002 7.71896 14.0951L11.1729 14.0873L11.3912 14.0255C12.2027 13.8037 12.7574 13.2572 12.9603 12.4889C13.0656 12.0893 13.0527 11.1354 12.9295 10.3826C12.6598 8.70678 11.9767 7.70131 10.8956 7.38678C10.6491 7.31459 10.2074 7.26045 10.0764 7.28623C9.95057 7.30944 9.77594 7.40225 9.38047 7.65749C8.95931 7.93077 8.90025 7.9617 8.58438 8.0803C8.21972 8.21694 7.91926 8.27624 7.56745 8.27624C7.20792 8.27624 6.93058 8.22467 6.56592 8.09577C6.2218 7.97202 6.20639 7.96428 5.66711 7.62139C5.38463 7.44092 5.17405 7.32491 5.09187 7.3017C4.94806 7.26561 4.94806 7.26561 4.65531 7.29655Z" />
                                    </g>
                                </svg>
                            </a></li>
                    @endauth
                </ul>
                <form class="mobile-menu-form" action="{{ route('product') }}" method="GET">
                    <div class="input-with-btn d-flex flex-column">
                        <input type="text" name="search" placeholder="Nhập nội dung tìm kiếm...">
                        <button class="primary-btn6" type="submit">Tìm kiếm</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="nav-right d-flex jsutify-content-end align-items-center">
            <ul>
                <li class="search-btn"><a>
                        <svg width="15" height="15" viewBox="0 0 15 15" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M13.8914 12.3212L11.3164 9.74312C11.1877 9.63999 11.0332 9.56265 10.8787 9.56265H10.4667C11.1619 8.6603 11.5997 7.52593 11.5997 6.26265C11.5997 3.32358 9.1792 0.900146 6.2437 0.900146C3.28245 0.900146 0.887695 3.32358 0.887695 6.26265C0.887695 9.22749 3.28245 11.6251 6.2437 11.6251C7.4797 11.6251 8.6127 11.2126 9.5397 10.4908V10.9291C9.5397 11.0837 9.5912 11.2384 9.71995 11.3673L12.2692 13.9197C12.5267 14.1775 12.9129 14.1775 13.1447 13.9197L13.8657 13.1978C14.1232 12.9658 14.1232 12.5791 13.8914 12.3212ZM6.2437 9.56265C4.41545 9.56265 2.9477 8.09312 2.9477 6.26265C2.9477 4.45796 4.41545 2.96265 6.2437 2.96265C8.0462 2.96265 9.5397 4.45796 9.5397 6.26265C9.5397 8.09312 8.0462 9.56265 6.2437 9.56265Z" />
                        </svg>
                    </a>
                    <form class="nav__search-form" action="{{ route('product') }}" method="GET">
                        <input type="text" name="search" placeholder="Nhập nội dung tìm kiếm" id="search"
                            autocomplete="off">
                        <button type="submit">
                            <svg width="15" height="15" viewBox="0 0 15 15" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M13.8914 12.3212L11.3164 9.74312C11.1877 9.63999 11.0332 9.56265 10.8787 9.56265H10.4667C11.1619 8.6603 11.5997 7.52593 11.5997 6.26265C11.5997 3.32358 9.1792 0.900146 6.2437 0.900146C3.28245 0.900146 0.887695 3.32358 0.887695 6.26265C0.887695 9.22749 3.28245 11.6251 6.2437 11.6251C7.4797 11.6251 8.6127 11.2126 9.5397 10.4908V10.9291C9.5397 11.0837 9.5912 11.2384 9.71995 11.3673L12.2692 13.9197C12.5267 14.1775 12.9129 14.1775 13.1447 13.9197L13.8657 13.1978C14.1232 12.9658 14.1232 12.5791 13.8914 12.3212ZM6.2437 9.56265C4.41545 9.56265 2.9477 8.09312 2.9477 6.26265C2.9477 4.45796 4.41545 2.96265 6.2437 2.96265C8.0462 2.96265 9.5397 4.45796 9.5397 6.26265C9.5397 8.09312 8.0462 9.56265 6.2437 9.56265Z" />
                            </svg>
                        </button>
                    </form>
                </li>
                @auth
                    <li><a href="{{ route('wishlist') }}">
                            <svg width="14" height="13" viewBox="0 0 14 13" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M12.4147 1.51371C11.0037 0.302997 8.92573 0.534835 7.61736 1.87434L7.12993 2.38954L6.61684 1.87434C5.33413 0.534835 3.23047 0.302997 1.81948 1.51371C0.203258 2.90473 0.126295 5.37767 1.56294 6.87174L6.53988 12.0237C6.84773 12.3586 7.38647 12.3586 7.69433 12.0237L12.6713 6.87174C14.1079 5.37767 14.0309 2.90473 12.4147 1.51371Z" />
                            </svg>
                        </a></li>
                    <li class="search-btn">
                        @php
                            $carts = [];
                            if (Auth::check()) {
                                $carts = App\Models\Cart::where('user_id', Auth::user()->id)->get();
                            } else {
                                $carts = App\Models\Cart::where(
                                    'guest_user_id',
                                    request()->cookie('guest_user_id'),
                                )->get();
                            }
                        @endphp
                        <a>
                            <svg width="16" height="13" viewBox="0 0 16 13" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M15.6365 5.46266C15.6365 5.12721 15.3541 4.84336 15.0202 4.84336H13.274L10.5262 1.07601C10.2694 0.688956 9.75576 0.611544 9.39624 0.895386C9.01104 1.15342 8.934 1.6695 9.21648 2.03075L11.2452 4.84336H5.21036L7.2391 2.03075C7.52158 1.6695 7.44454 1.15342 7.05934 0.895386C6.69982 0.611544 6.18621 0.688956 5.92941 1.07601L3.18163 4.84336H1.46105C1.10153 4.84336 0.844727 5.12721 0.844727 5.46266V5.87552C0.844727 6.23677 1.10153 6.49481 1.46105 6.49481H1.66649L2.33418 11.2169C2.41122 11.8362 2.92482 12.2749 3.54115 12.2749H12.9144C13.5308 12.2749 14.0444 11.8362 14.1214 11.2169L14.8148 6.49481H15.0202C15.3541 6.49481 15.6365 6.23677 15.6365 5.87552V5.46266ZM8.85696 10.0041C8.85696 10.3654 8.57447 10.6234 8.24063 10.6234C7.88111 10.6234 7.6243 10.3654 7.6243 10.0041V7.1141C7.6243 6.77865 7.88111 6.49481 8.24063 6.49481C8.57447 6.49481 8.85696 6.77865 8.85696 7.1141V10.0041ZM11.7331 10.0041C11.7331 10.3654 11.4507 10.6234 11.1168 10.6234C10.7573 10.6234 10.5005 10.3654 10.5005 10.0041V7.1141C10.5005 6.77865 10.7573 6.49481 11.1168 6.49481C11.4507 6.49481 11.7331 6.77865 11.7331 7.1141V10.0041ZM5.98077 10.0041C5.98077 10.3654 5.69829 10.6234 5.36445 10.6234C5.00492 10.6234 4.74812 10.3654 4.74812 10.0041V7.1141C4.74812 6.77865 5.00492 6.49481 5.36445 6.49481C5.69829 6.49481 5.98077 6.77865 5.98077 7.1141V10.0041Z" />
                            </svg>
                        </a>
                        <span
                            class="cart-counter badge rounded-circle p-0 {{ count($carts) > 0 ? '' : 'd-none' }}">{{ count($carts) }}</span>


                        <form class="nav__search-form w-100  dropdown-main gshop-header-user p-0">
                            <div class="gshop-header-cart position-relative">

                                <div class="cart-box-wrapper w-100">
                                    <div class="apt_cart_box theme-scrollbar">
                                        <ul class="at_scrollbar scrollbar cart-navbar-wrapper">
                                            @include('clients.partials.cart-navbar', [
                                                'carts' => $carts,
                                            ])
                                        </ul>
                                        <div class="d-flex align-items-center justify-content-between mt-3">
                                            <h6 class="mb-0">Tổng:</h6>
                                            <span
                                                class="fw-semibold sub-total-price text-white">{{ format_cash(getSubTotal($carts, false)) }}</span>
                                        </div>
                                        <div class="row align-items-center justify-content-between btn-group w-100 m-auto">
                                            <div class="col-6">
                                                <a class="primary-btn6 btn-md mt-4 w-100"
                                                    style="height:40px;border-radius:5px;padding: 10px 28px;background:#F46F30"
                                                    href="{{ route('cart') }}">Giỏ hàng</a>
                                            </div>
                                            <div class="col-6">
                                                <a class="primary-btn6 btn-md mt-4 w-100"
                                                    style="height:40px;border-radius:5px;padding: 10px 28px;background:#F46F30"
                                                    href="{{ route('checkout') }}">Thanh
                                                    toán</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </li>
                    <li class="search-btn"><a>
                            <svg width="15" height="15" viewBox="0 0 15 15" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M8 7C9.65685 7 11 5.65685 11 4C11 2.34315 9.65685 1 8 1C6.34315 1 5 2.34315 5 4C5 5.65685 6.34315 7 8 7Z">
                                </path>
                                <path d="M14 12C14 10.3431 12.6569 9 11 9H5C3.34315 9 2 10.3431 2 12V15H14V12Z"></path>
                            </svg>
                        </a>
                        <form class="nav__search-form w-10  dropdown-main">
                            <ul>
                                <li><a href="{{ route('account') }}">Tài khoản </a></li>
                                <li><a href="{{ route('account') }}#don-hang">Đơn hàng đã mua</a></li>
                                <li><a href="{{ route('account') }}#dia-chi">Danh sách địa chỉ</a></li>
                                @if (auth()->user()->role == 9 || auth()->user()->role == 10)
                                    <li class="text-danger"><a href="{{ route('admin.home') }}">Quản lý</a></li>
                                @endif
                                <li><a href="{{ route('logout') }}">Đăng xuất</a></li>
                            </ul>
                        </form>
                    </li>
                @else
                    <li class="search-btn">
                        @php
                            $carts = [];
                            if (Auth::check()) {
                                $carts = App\Models\Cart::where('user_id', Auth::user()->id)->get();
                            } else {
                                $carts = App\Models\Cart::where(
                                    'guest_user_id',
                                    request()->cookie('guest_user_id'),
                                )->get();
                            }
                        @endphp
                        <a>
                            <svg width="16" height="13" viewBox="0 0 16 13" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M15.6365 5.46266C15.6365 5.12721 15.3541 4.84336 15.0202 4.84336H13.274L10.5262 1.07601C10.2694 0.688956 9.75576 0.611544 9.39624 0.895386C9.01104 1.15342 8.934 1.6695 9.21648 2.03075L11.2452 4.84336H5.21036L7.2391 2.03075C7.52158 1.6695 7.44454 1.15342 7.05934 0.895386C6.69982 0.611544 6.18621 0.688956 5.92941 1.07601L3.18163 4.84336H1.46105C1.10153 4.84336 0.844727 5.12721 0.844727 5.46266V5.87552C0.844727 6.23677 1.10153 6.49481 1.46105 6.49481H1.66649L2.33418 11.2169C2.41122 11.8362 2.92482 12.2749 3.54115 12.2749H12.9144C13.5308 12.2749 14.0444 11.8362 14.1214 11.2169L14.8148 6.49481H15.0202C15.3541 6.49481 15.6365 6.23677 15.6365 5.87552V5.46266ZM8.85696 10.0041C8.85696 10.3654 8.57447 10.6234 8.24063 10.6234C7.88111 10.6234 7.6243 10.3654 7.6243 10.0041V7.1141C7.6243 6.77865 7.88111 6.49481 8.24063 6.49481C8.57447 6.49481 8.85696 6.77865 8.85696 7.1141V10.0041ZM11.7331 10.0041C11.7331 10.3654 11.4507 10.6234 11.1168 10.6234C10.7573 10.6234 10.5005 10.3654 10.5005 10.0041V7.1141C10.5005 6.77865 10.7573 6.49481 11.1168 6.49481C11.4507 6.49481 11.7331 6.77865 11.7331 7.1141V10.0041ZM5.98077 10.0041C5.98077 10.3654 5.69829 10.6234 5.36445 10.6234C5.00492 10.6234 4.74812 10.3654 4.74812 10.0041V7.1141C4.74812 6.77865 5.00492 6.49481 5.36445 6.49481C5.69829 6.49481 5.98077 6.77865 5.98077 7.1141V10.0041Z" />
                            </svg>
                        </a>
                        <span
                            class="cart-counter badge rounded-circle p-0 {{ count($carts) > 0 ? '' : 'd-none' }}">{{ count($carts) }}</span>


                        <form class="nav__search-form w-100  dropdown-main gshop-header-user p-0">
                            <div class="gshop-header-cart position-relative">

                                <div class="cart-box-wrapper w-100">
                                    <div class="apt_cart_box theme-scrollbar">
                                        <ul class="at_scrollbar scrollbar cart-navbar-wrapper">
                                            @include('clients.partials.cart-navbar', [
                                                'carts' => $carts,
                                            ])
                                        </ul>
                                        <div class="d-flex align-items-center justify-content-between mt-3">
                                            <h6 class="mb-0">Tổng:</h6>
                                            <span
                                                class="fw-semibold sub-total-price text-white">{{ format_cash(getSubTotal($carts, false)) }}</span>
                                        </div>
                                        <div class="row align-items-center justify-content-between btn-group w-100 m-auto">
                                            <div class="col-6">
                                                <a class="primary-btn6 btn-md mt-4 w-100"
                                                    style="height:40px;border-radius:5px;padding: 10px 28px;background:#F46F30"
                                                    href="{{ route('cart') }}">Giỏ hàng</a>
                                            </div>
                                            <div class="col-6">
                                                <a class="primary-btn6 btn-md mt-4 w-100"
                                                    style="height:40px;border-radius:5px;padding: 10px 28px;background:#F46F30"
                                                    href="{{ route('checkout') }}">Thanh
                                                    toán</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </li>
                    <li class="search-btn"><a>
                            <svg width="15" height="15" viewBox="0 0 15 15" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M8 7C9.65685 7 11 5.65685 11 4C11 2.34315 9.65685 1 8 1C6.34315 1 5 2.34315 5 4C5 5.65685 6.34315 7 8 7Z">
                                </path>
                                <path d="M14 12C14 10.3431 12.6569 9 11 9H5C3.34315 9 2 10.3431 2 12V15H14V12Z"></path>
                            </svg>
                        </a>
                        <form class="nav__search-form w-10  dropdown-main">
                            <ul>
                                <li><a href="{{ route('login') }}">Đăng nhập </a></li>
                                <li><a href="{{ route('signup') }}">Đăng ký</a></li>
                            </ul>
                        </form>
                    </li>


                @endauth
            </ul>
            <div class="sidebar-button mobile-menu-btn ">
                <i class="bi bi-list"></i>
            </div>
        </div>
    </div>
</header>
