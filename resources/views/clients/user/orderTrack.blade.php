@extends('layouts.clients')
@section('css')
@endsection
@section('content')
    <div class="login-section h1-story-area dashboard-section h2-contact-area  rounded-10 shadow-soft two mb-120 pt-120">
        <div class="container form-wrapper wow fadeInUp  z-1000">
            <div class="order-tracking-wrap rounded py-3 px-4">
                <div class="section-title1">
                    <span><img src="{{ asset('assets/clients/images/icon/section-vec-l1.svg') }}" alt="">Theo dõi đơn hàng
                        <img src="{{ asset('assets/clients//images/icon/section-vec-r1.svg') }}" alt=""></span>
                </div>
                <form class="d-flex flex-column align-items-center mb-5 justify-content-center"
                    action="{{ route('trackOrder') }}">
                    <div class="form-inner w-50">
                        <label>Mã đơn hàng *</label>
                        <input type="text" class="w-100" name="code" placeholder="Mã đơn hàng" value="{{ old('code',$searchCode ?? NULL) }}">
                    </div>
                    <div class="form-inner">
                        @csrf
                        <button class="primary-btn3" type="submit">Đặt lịch ngay</button>
                    </div>
                </form>


                @isset($order)
                    <ol id="progress-bar">
                        <li class="fs-xs tt-step @if ($order->order_status != 'CANCEL') tt-step-done @else @endif">
                            Đơn hàng đã tạo</li>
                        <li
                            class="fs-xs tt-step @if (
                                ($order->shipment_status != 'CANCEL' && $order->shipment_status != 'ORDERPLACE') ||
                                    ($order->order_status == 'PAID' && $order->shipment_status == 'PACKED')) tt-step-done @elseif ($order->order_status == 'PAID' && $order->shipment_status == 'PACKED') active @endif">
                            Đã nhận và đang đóng gói</li>
                        <li
                            class="fs-xs tt-step @if (
                                $order->shipment_status == 'SHIPPED' ||
                                    $order->shipment_status == 'INTRANSIT' ||
                                    $order->shipment_status == 'OUTFORDELIVERY' ||
                                    $order->shipment_status == 'DELAYED' ||
                                    $order->shipment_status == 'EXCEPTION' ||
                                    $order->shipment_status == 'OUTFORDELIVERY' ||
                                    $order->shipment_status == 'DELIVERED' ||
                                    $order->shipment_status == 'RETURNED') tt-step-done @elseif ($order->shipment_status == 'SHIPPED') active @endif">
                            Đã được vận chuyển</li>
                        <li class="fs-xs tt-step @if ($order->shipment_status == 'DELIVERED') tt-step-done @endif">
                            Giao hàng thành công</li>
                    </ol>

                    <div class="table-responsive-md mt-5">
                        <table class="table table-bordered fs-xs">
                            <thead>
                                <tr>
                                    <th scope="col">Thời gian</th>
                                    <th scope="col">Thông tin</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td> {{ \Carbon\Carbon::parse($order->order_date)->format('d-m-Y H:i:s') }} </td>
                                    <td>Đơn hàng đang được xử lý</td>
                                </tr>
                                @if ($order->orderUpdates && $order->orderUpdates->isNotEmpty())
                                    @foreach ($order->orderUpdates as $orderUpdate)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($orderUpdate->created_at)->format('d-m-Y H:i:s') }}
                                            </td>
                                            <td>{{ $orderUpdate->note }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                            </tbody>
                        </table>
                    </div>
                @endisset


            </div>
        </div>
    </div>

@endsection

@section('js')
@endsection
