@extends('layouts.admin')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/select2/select2.css') }}" />
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Chi tiết hoá đơn #{{ $order->id }}</h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-xl-9 order-2 order-md-2 order-lg-2 order-xl-1">
                        <div class="card mb-4" id="section-1">
                            <div class="card-header border-bottom-0">

                                <div class="row justify-content-between align-items-center g-3">
                                    <div class="col-auto flex-grow-1">
                                        <h5 class="mb-0">Hoá đơn
                                            <span class="text-accent">#{{ $order->id }}
                                            </span>
                                        </h5>
                                        <span class="fs-6">Ngày đặt hàng:
                                            {{ \Carbon\Carbon::parse($order->order_date)->format('d-m-Y H:i:s') }}
                                        </span>

                                    </div>

                                    <div class="col-auto col-lg-3">
                                        <label class="form-label">Trạng thái thanh toán</label>
                                        <div class="input-group">
                                            <select class="form-select select2" name="payment_status"
                                                data-minimum-results-for-search="Infinity" id="update_payment_status">
                                                <option value="" disabled>
                                                    Trạng thái thanh toán
                                                </option>
                                                <option value="PAID" @if ($order->payment_status == 'PAID') selected @endif>
                                                    Đã thanh toán</option>
                                                <option value="PENDING" @if ($order->payment_status == 'PENDING') selected @endif>
                                                    Chưa thanh toán
                                                </option>
                                                <option value="CANCELED" @if ($order->payment_status == 'CANCELED') selected @endif>
                                                    Đã huỷ
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row justify-content-between g-3">
                                    <div class="col-xl-7 col-lg-6">
                                        <div class="welcome-message">
                                            <h6 class="mb-2">Thông tin khách hàng</h6>
                                            <p class="mb-0">Tên: {{ limitText($order->address->name, 25) }}
                                            </p>
                                            <p class="mb-0">Email: {{ $order->user->email }}
                                            </p>
                                            <p class="mb-0">Số điện thoại: {{ $order->address->phone }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-xl-5 col-lg-6">
                                        <div class="shipping-address d-flex justify-content-md-end">
                                            <div class="pe-2">
                                                <h6 class="mb-2">Địa chỉ vận chuyển</h6>
                                                <p class="mb-0">
                                                    {{ formatAddress($order->address) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <table class="table tt-footable border-top" data-use-parent-width="true">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="7%">STT</th>
                                        <th>Thông tin</th>
                                        <th data-breakpoints="xs sm">Giá</th>
                                        <th data-breakpoints="xs sm">Số lượng</th>
                                        <th data-breakpoints="xs sm" class="text-end">Tổng</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php
                                        $totalOrderAmount = 0;
                                    @endphp
                                    @foreach ($order->orderItems as $key => $item)
                                        @php
                                            $product = $item->product_variation->productWithTrashed;
                                            $itemTotalPrice = $item->product_variation->price * $item->quantity;
                                            $totalOrderAmount += $itemTotalPrice;
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $key + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm"> <img data-fancybox
                                                            data-caption="{{ $product->name }}"
                                                            src="{{ getImage($product->image) }}"
                                                            alt="{{ $product->name }}" class="rounded-circle">
                                                    </div>
                                                    <div class="ms-2">
                                                        <h6 class="fs-sm mb-0">
                                                            {{ $product->name }}
                                                        </h6>
                                                        <div class="text-muted">
                                                            @foreach (generateVariationOptions($item->product_variation->combinations) as $variation)
                                                                <span class="fs-xs">
                                                                    {{ $variation['name'] }}:
                                                                    @foreach ($variation['values'] as $value)
                                                                        {{ $value['name'] }}
                                                                    @endforeach
                                                                    @if (!$loop->last)
                                                                        ,
                                                                    @endif
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="tt-tb-price">
                                                <span class="fw-bold">{{ format_cash($item->product_variation->price) }}
                                                </span>
                                            </td>
                                            <td class="fw-bold">{{ $item->quantity }}</td>

                                            <td class="tt-tb-price text-end">
                                                <span
                                                    class="text-accent fw-bold">{{ format_cash($item->product_variation->price * $item->quantity) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="card-body">
                                <div class="card-footer border-top-0 px-4 py-3 rounded">
                                    <div class="row g-4 d-flex justify-content-between">
                                        <div class="col-auto">
                                            <h6 class="mb-1">Phương thức thanh toán</h6>
                                            <span>{{ getTextPayment($order->payment_method) }}</span>
                                        </div>

                                        <div class="col-auto">
                                            <div class="row g-4">
                                                <div class="col-auto">
                                                    <h6 class="mb-1">Tổng tiền</h6>
                                                    <strong>{{ format_cash($totalOrderAmount) }}</strong>
                                                </div>

                                                @if ($order->coupon_discount_amount > 0)
                                                    <div class="col-auto ps-lg-5">
                                                        <h6 class="mb-1">Giảm giá</h6>
                                                        <strong>
                                                            @if ($order->coupon_discount_amount <= 100)
                                                                {{ $order->coupon_discount_amount }}%
                                                            @else
                                                                {{ format_cash($order->coupon_discount_amount) }}
                                                            @endif
                                                        </strong>
                                                    </div>
                                                @endif

                                                <div class="col-auto text-lg-end ps-lg-5">
                                                    <h6 class="mb-1">Thành tiền</h6>
                                                    <strong
                                                        class="text-accent">{{ format_cash($order->total_amount) }}</strong>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 order-1 order-md-1 order-lg-1 order-xl-2">
                        <div class="tt-sticky-sidebar">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="mb-5">Lịch sử vận chuyển</h5>
                                    <div class="tt-vertical-step">
                                        <ul class="timeline timeline-outline">
                                            @forelse ($order->orderUpdates as $orderUpdate)
                                                <li class="timeline-item timeline-item-transparent">
                                                    {{-- điều kiện để kiểm tra xem liệu đây có phải là phần tử cuối cùng --}}
                                                    <span class="timeline-point timeline-point-primary"
                                                        style="{{ $loop->last ? '' : 'background-color: #7367f0 !important;' }}"></span>
                                                    <a class="{{ $loop->first ? 'timeline-event active' : '' }}">
                                                        {{ $orderUpdate->note }}
                                                        <br>
                                                        {{ \Carbon\Carbon::parse($orderUpdate->created_at)->format('d-m-Y H:i:s') }}.</a>
                                                </li>
                                            @empty
                                                <li>
                                                    <a class="active">Không có lịch sử nào</a>
                                                </li>
                                            @endforelse
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('assets/admin/vendor/libs/select2/select2.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script src="{{ asset('assets/admin/vendor/libs/block-ui/block-ui.js') }}"></script>

    <script src="{{ asset('assets/admin/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/libs/jquery-timepicker/jquery-timepicker.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/libs/pickr/pickr.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/libs/flatpickr/vn.js') }}?v=1"></script>
    <script>
        $(document).ready(function() {

            Fancybox.bind("[data-fancybox]", {
                infinite: false,
                transitionEffect: "fade",
                buttons: ["zoom", "close"],
                loop: false,
                toolbar: true,
                thumbs: {
                    autoStart: true
                },
                zoom: true,
            });
        });
    </script>
@endsection
