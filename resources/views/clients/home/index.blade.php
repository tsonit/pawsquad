@extends('layouts.clients')
@section('css')
    <style>
        .hero3-slider .swiper-slide {
            height: 500px;
            display: flex;
            align-items: center;
            object-fit: cover;
        }
    </style>
@endsection
@section('content')
    <div class="hero3 mb-90">
        @if(getOption('DB_SHORT_STANDOUT_NOTIFICATION'))
        <div class="background-text">
            <h2 class="marquee_text"><img src="{{ asset('assets/clients/images/icon/marque-foot.svg') }}"
                    alt="image">
                    <span>{{ getOption('DB_SHORT_STANDOUT_NOTIFICATION') }}</span> <img
                    src="{{ asset('assets/clients/images/icon/marque-foot.svg') }}" alt="image">
                    <span>{{ getOption('DB_SHORT_STANDOUT_NOTIFICATION') }}</span> </h2>
        </div>
        @endif
        <div class="swiper hero3-slider">
            <div class="swiper-wrapper">
                @if ($sliders && $sliders->isNotEmpty())
                    @foreach ($sliders as $slide)
                        @if ($slide->title)
                            <div class="swiper-slide">
                                <div class="hero-wrapper">
                                    <div class="container">
                                        <div class="row align-items-center">
                                            <div class="col-lg-6">
                                                <div class="banner-content">
                                                    <div class="d-flex">
                                                        @if ($slide->category)
                                                            <h6>{{ $slide->danhmuc->name }}</h6>
                                                        @endif
                                                        @if ($slide->price)
                                                            <p class="fs-5"
                                                                style="margin-bottom: 20px;
                                                        font-size: 1.4rem;
                                                        font-weight: 400;
                                                        color: var(--primary-color3);
                                                        font-family: var(--font-cabin);
                                                        text-transform: capitalize;
                                                        position: relative;
                                                        padding-left: 43px;">
                                                                -
                                                                @if ($slide->price <= 100)
                                                                    {{ $slide->price }}%
                                                                @else
                                                                    {{ format_cash($slide->price) }}
                                                                @endif
                                                            </p>
                                                        @endif
                                                    </div>

                                                    @if ($slide->title)
                                                        <h1 class="mb-3">{{ $slide->title }}</h1>
                                                    @endif
                                                    @if ($slide->description)
                                                        <h3 class="mb-3">{{ $slide->description }}</h3>
                                                    @endif
                                                    @if ($slide->button_text)
                                                        <div class="btn-group">
                                                            <a class="primary-btn6"
                                                                href="{{ $slide->button_link_text ?? '#' }}">{{ $slide->button_text }}</a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            @if ($slide->image)
                                                <div class="col-lg-6 d-flex justify-content-end">
                                                    <div class="hero-img">
                                                        <img class="img-fluid banner-imgas"
                                                            src="{{ getImage($slide->image) }}"
                                                            alt="{{ $slide->title ?? null }}">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="swiper-slide">
                                <img src="{{ getImage($slide->image) }}" class="w-100 h-100 object-cover"
                                    @if ($slide->button_link_text) onclick="window.location.href='{{ $slide->button_link_text }}'" @endif
                                    style="cursor: pointer;">
                            </div>
                        @endif
                    @endforeach
                @else
                    <div class="swiper-slide">
                        <div class="hero-wrapper">
                            <div class="container">
                                <div class="row align-items-center">
                                    <div class="col-lg-6">
                                        <div class="banner-content">
                                            <h6>Thức ăn cho chó</h6>
                                            <h1>Giảm giá tất cả thức ăn cho chó lên tới 50%</h1>
                                            <div class="btn-group">
                                                <a class="primary-btn5 btn-md" href="shop.html">Cửa hàng</a>
                                                <a class="primary-btn6" href="shop-details.html">Xem chi tiết</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 d-flex justify-content-end">
                                        <div class="hero-img">
                                            <img class="img-fluid banner-imgas"
                                                src="{{ asset('assets/clients/images/bg/h3-banner-img.png') }}" alt>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="right-sidebar">
            <div class="slider-pagination-area">
                <div class="h3-hero-slider-pagination"></div>
            </div>
        </div>
    </div>

    @if (isset($categories) && $categories->isNotEmpty())
        <div class="home3-categoty-area pt-120 mb-120">
            <div class="container">
                <div class="row mb-60">
                    <div class="col-lg-12 d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="section-title3">
                            <h2><img src="{{ asset('assets/clients/images/icon/h3-sec-tt-vect-left.svg') }}" alt><span>
                                    Danh mục</span><img
                                    src="{{ asset('assets/clients/images/icon/h3-sec-tt-vect-right.svg') }}" alt></h2>
                        </div>
                        <div class="slider-btn-wrap">
                            <div class="slider-btn prev-btn-11">
                                <i class="bi bi-arrow-left"></i>
                            </div>
                            <div class="slider-btn next-btn-11">
                                <i class="bi bi-arrow-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 d-flex justify-content-center">
                        <div class="swiper h3-category-slider">
                            <div class="swiper-wrapper">
                                @foreach ($categories as $category)
                                    <div class="swiper-slide">
                                        <div class="category-card">
                                            <a href="{{ route('list.category', ['slug' => $category->slug]) }}"
                                                class="category-card-inner">
                                                <div class="category-card-front">
                                                    <div class="category-icon">
                                                        <img src="{{ getImage($category->image) }}"
                                                            alt="{{ $category->name }}">
                                                    </div>
                                                    <div class="content">
                                                        <h4>{{ $category->name }}</h4>
                                                    </div>
                                                </div>
                                                <div class="category-card-back">
                                                    <img src="{{ getImage($category->image) }}"
                                                        alt="{{ $category->name }}">
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="home3-collection-area mb-120">
        <div class="container">
            <div class="row mb-60">
                <div class="col-lg-12 d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="section-title3">
                        <h2><img src="{{ asset('assets/clients/images/icon/h3-sec-tt-vect-left.svg') }}" alt><span>Sản
                                phẩm</span><img src="{{ asset('assets/clients/images/icon/h3-sec-tt-vect-right.svg') }}"
                                alt></h2>
                    </div>
                    <div class="h3-view-btn d-md-flex d-none">
                        <a href="{{ route('product') }}">Xem tất cả<img
                                src="{{ asset('assets/clients/images/icon/haf-button-2.svg') }}" alt></a>
                    </div>
                </div>
            </div>
            <div class="row g-4 justify-content-center">
                @include('clients.partials.product2', ['products' => $products])
            </div>
            <div class="row d-md-none d-block pt-30">
                <div class="col-lg-12 d-flex justify-content-center">
                    <div class="h3-view-btn">
                        <a href="{{ route('product') }}">Xem tất cả<img
                                src="{{ asset('assets/clients/images/icon/haf-button-2.svg') }}" alt="Xem tất cả"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="h3-offer-area mb-120">
        <div class="container-fluid p-0 overflow-hidden">
            <div class="row">
                <div class="col-lg-6 p-0">
                    <div class="offer-left">
                        <div class="offer-content">
                            <span>Giảm giá 50%</span>
                            <h2>Nguyên liệu cho chó dạng gói.</h2>
                            <a class="primary-btn6" href="shop.html">Xem ngay</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 p-0">
                    <div class="offer-right">
                        <div class="slider-btn-wrap">
                            <div class="slider-btn prev-btn-15 mb-40">
                                <i class="bi bi-arrow-up"></i>
                            </div>
                            <div class="slider-btn next-btn-15">
                                <i class="bi bi-arrow-down"></i>
                            </div>
                        </div>
                        <div class="countdown-timer">
                            <ul data-countdown="2024-12-23">
                                <li data-days="00">00</li>
                                <li data-hours="00">00</li>
                                <li data-minutes="00">00</li>
                                <li data-seconds="00">00</li>
                            </ul>
                        </div>
                        <div class="row position-relative">
                            <div class="swiper h3-offer-slider">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <div class="offer-right-card">
                                            <div class="offer-img">
                                                <img class="img-fluid"
                                                    src="{{ asset('assets/clients/images/bg/h3-offer-right.png') }}" alt>
                                            </div>
                                            <div class="offer-content">
                                                <span>Giới hạn</span>
                                                <h2 class="title-limit-2">Thức ăn cho chó Whiskas Gà quay</h2>
                                                <div class="price">
                                                    <h6>120.000đ</h6>
                                                    <del>200.000đ</del>
                                                </div>
                                                <a class="primary-btn6" href="shop.html">Xem ngay</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="offer-right-card">
                                            <div class="offer-img">
                                                <img class="img-fluid"
                                                    src="{{ asset('assets/clients/images/bg/h3-offer-right.png') }}" alt>
                                            </div>
                                            <div class="offer-content">
                                                <span>Giới hạn</span>
                                                <h2 class="title-limit-2">Thức ăn cho chó Whiskas Gà quay</h2>
                                                <div class="price">
                                                    <h6>120.000đ</h6>
                                                    <del>200.000đ</del>
                                                </div>
                                                <a class="primary-btn6" href="shop.html">Xem ngay</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="h2-services-area mb-120">
        <div class="services-top">
            <div class="services-section-card">
                <div class="card-vector">
                    <img class="services-card-vect-01" src="assets/images/bg/h2-services-title-rt.png" alt>
                    <img class="services-card-vect-02" src="assets/images/bg/h2-services-title-lb.png" alt>
                </div>
                <div class="services-title">
                    <h2>Tất cả <span>dịch vụ.</span></h2>
                    <a class="primary-btn2" href="contact.html">Đặt lịch ngay</a>
                </div>
            </div>
            <div class="swiper h2-services-slider">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="services-card daycare-card d-flex flex-column h-100">
                            <div class="card-vector">
                                <img class="services-card-vect-01" src="assets/images/bg/services-card-vec.png" alt>
                                <img class="services-card-vect-02" src="assets/images/bg/services-card-vec2.png" alt>
                            </div>
                            <div class="services-icon">
                                <img src="assets/images/icon/daycare3.svg" alt>
                            </div>
                            <div class="services-content mt-auto">
                                <h3><a href="service-details.html">Lưu trú</a></h3>
                                <p>Dịch vụ lưu trú trông giữ chó mèo PawSquad tự hào là khách sạn chó mèo hiện đại, thân
                                    thiện nhất tại thành phố. Giúp bạn yên tâm cho bé nhất.</p>
                                <div class="more-btn">
                                    <a href="shop-details.html">Xem chi tiết<img src="assets/images/icon/h2-btn-arrow.svg"
                                            alt></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="services-card grooming-card d-flex flex-column h-100">
                            <div class="card-vector">
                                <img class="services-card-vect-01" src="assets/images/bg/services-card-vec.png" alt>
                                <img class="services-card-vect-02" src="assets/images/bg/services-card-vec2.png" alt>
                            </div>
                            <div class="services-icon">
                                <img src="assets/images/icon/grooming3.svg" alt>
                            </div>
                            <div class="services-content mt-auto">
                                <h3><a href="service-details.html">Cắt tỉa</a></h3>
                                <p>Dịch vụ cắt tỉa lông tạo kiểu cho chó mèo tại PawSquad chúng tôi “cân đo đong đếm” xem
                                    kiểu tóc nào đẹp và phù hợp nhất các bé nhất.</p>
                                <div class="more-btn">
                                    <a href="service-details.html">Xem chi tiết<img
                                            src="assets/images/icon/h2-btn-arrow.svg" alt></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="services-card boarding-card d-flex flex-column h-100">
                            <div class="card-vector">
                                <img class="services-card-vect-01" src="assets/images/bg/services-card-vec.png" alt>
                                <img class="services-card-vect-02" src="assets/images/bg/services-card-vec2.png" alt>
                            </div>
                            <div class="services-icon">
                                <img src="assets/images/icon/bording3.svg" alt>
                            </div>
                            <div class="services-content mt-auto">
                                <h3><a href="service-details.html">Grooming</a></h3>
                                <p>PawSquad ra đời bởi những người trẻ tuổi, hết lòng yêu thương những thú cưng nên đội ngũ
                                    của chúng tôi cũng thấm nhuần tư tưởng đó.</p>
                                <div class="more-btn">
                                    <a href="service-details.html">Xem chi tiết<img
                                            src="assets/images/icon/h2-btn-arrow.svg" alt></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class=" h2-contact-area row justify-content-center">
            <div class="col-lg-10">
                <div class="row">
                    <div class="col-lg-8 ">
                        @include('clients.partials.booking')
                    </div>
                    <div class="col-lg-4 d-md-block d-none">
                        <div class="contact-img mt-5">
                            <img class="img-fluid" src="{{ asset('assets/clients/images/bg/h2-contact-img.png') }}"
                                alt="contact-img">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="home3-blog-area mb-120">
        <div class="container">
            <div class="row mb-60">
                <div class="col-lg-12 d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="section-title3">
                        <h2><img src="{{ asset('assets/clients/images/icon/h3-sec-tt-vect-left.svg') }}" alt><span>Bài
                                viết</span><img src="{{ asset('assets/clients/images/icon/h3-sec-tt-vect-right.svg') }}"
                                alt></h2>
                    </div>
                    <div class="h3-view-btn d-md-flex d-none">
                        <a href="blog-grid.html">Xem tất cả<img
                                src="{{ asset('assets/clients/images/icon/haf-button-2.svg') }}" alt></a>
                    </div>
                </div>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-lg-4 col-md-6 col-sm-10">
                    <div class="blog-card3">
                        <div class="blog-img">
                            <img class="img-fluid" src="{{ asset('assets/clients/images/blog/blog7.png') }}" alt>
                        </div>
                        <div class="bolg-content">
                            <div class="cetagoty">
                                <a href="blog-grid.html">Khuyến mãi</a>
                            </div>
                            <div class="blog-meta">
                                <ul>
                                    <li><a href="blog-grid.html">Trung Kiên</a></li>
                                    <li><a href="blog-grid.html">27/09/2024</a></li>
                                </ul>
                            </div>
                            <h4><a href="blog-details.html">Khuyến mãi 50% cho tất cả dịch vụ</a></h4>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-10">
                    <div class="blog-card3">
                        <div class="blog-img">
                            <img class="img-fluid" src="{{ asset('assets/clients/images/blog/blog8.png') }}" alt>
                        </div>
                        <div class="bolg-content">
                            <div class="cetagoty">
                                <a href="blog-grid.html">Kinh nghiệm</a>
                            </div>
                            <div class="blog-meta">
                                <ul>
                                    <li><a href="blog-grid.html">Trung Kiên</a></li>
                                    <li><a href="blog-grid.html">28/09/2024</a></li>
                                </ul>
                            </div>
                            <h4><a href="blog-details.html">Kinh nghiệm nuôi chó Labrador</a>
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-10">
                    <div class="blog-card3">
                        <div class="blog-img">
                            <img class="img-fluid" src="{{ asset('assets/clients/images/blog/blog9.png') }}" alt>
                        </div>
                        <div class="bolg-content">
                            <div class="cetagoty">
                                <a href="blog-grid.html">Huấn luyện</a>
                            </div>
                            <div class="blog-meta">
                                <ul>
                                    <li><a href="blog-grid.html">Trung Kiên</a></li>
                                    <li><a href="blog-grid.html">29/09/2024</a></li>
                                </ul>
                            </div>
                            <h4><a href="blog-details.html">Cách huấn luyện giúp chó vâng lời</a>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row d-md-none d-block pt-30">
                <div class="col-lg-12 d-flex justify-content-center">
                    <div class="h3-view-btn">
                        <a href="shop.html">Xem tất cả bài viết<img
                                src="{{ asset('assets/clients/images/icon/haf-button-2.svg') }}" alt></a>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="home3-newsletter-area">
        <div class="newsletter-img">
            <img class="img-fluid" src="{{ asset('assets/clients/images/bg/h3-newsletter-img.png') }}" alt>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="newsletter-wrap">
                        <div class="section-title3 mb-40">
                            <span>Đăng ký</span>
                            <h2 class="fs-1">Nhận thông báo của chúng tôi</h2>
                        </div>
                        <form id="subscriber-form" class="contact-form" method="POST" action="{{ route('subscriber.join') }}">
                            <div class="form-inner d-flex flex-column w-100 p-3">
                                <div class="w-100">
                                    <input type="text" class="form-control border-bottom mb-2" placeholder="Nhập họ tên"
                                        name="fullname_subscriber">
                                </div>
                                <div class="w-100 mt-2">
                                    <input type="text" class="form-control border-bottom mb-2" placeholder="Nhập địa chỉ email"
                                        name="email_subscriber">
                                </div>
                                @csrf
                                <button class="primary-btn6 btn-md my-2" style="height:40px;border-radius:5px;padding: 10px 28px;background:#F46F30">Đăng ký</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
