<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OrderControllerAdmin extends Controller
{
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $orders = Order::with(['address'])
                ->select([
                    'id',
                    'total_amount',
                    'user_id',
                    'order_date',
                    'coupon_id',
                    'address_id',
                    'order_status',
                    'shipment_status',
                    'payment_method',
                    'coupon_discount_amount'
                ]);
            if ($request->has('date_range') && !empty($request->get('date_range'))) {
                // Giả sử input là '2024-10-01 đến 2024-10-31'
                // Phân tách chuỗi thành 2 ngày
                $dates = explode(' đến ', $request->get('date_range'));
                if (count($dates) == 2) {
                    // Sử dụng Carbon để phân tích ngày tháng
                    $startDate = Carbon::createFromFormat('Y-m-d', trim($dates[0]))->startOfDay();
                    $endDate = Carbon::createFromFormat('Y-m-d', trim($dates[1]))->endOfDay();
                    $orders->whereBetween('order_date', [$startDate, $endDate]);
                }
            }
            $data = DataTables::of($orders)
                ->filter(function ($query) use ($request) {
                    if ($request->has('search') && !empty($request->get('search')['value'])) {
                        $search = $request->get('search')['value'];
                        $query->where(function ($q) use ($search) {
                            $q->where('id', $search)
                                ->orWhere('total_amount', 'like', "$search")
                                ->orWhereRaw("
                                CASE
                                    WHEN order_status = 'PAID' THEN 'Đã thanh toán'
                                    WHEN order_status = 'PENDING' THEN 'Chưa thanh toán'
                                    WHEN order_status = 'CANCELED' THEN 'Đã huỷ'
                                END LIKE ?
                            ", ["%{$search}%"])
                                ->orWhereRaw("
                                CASE
                                    WHEN payment_method = 'VNPAY' THEN 'VNPAY'
                                    WHEN payment_method = 'COD' THEN 'Tiền mặt'
                                END LIKE ?
                            ", ["%{$search}%"])
                                ->orWhereHas('address', function ($q) use ($search) {
                                    $q->where('name', 'like', "%{$search}%")
                                        ->orWhere('phone', 'like', "%{$search}%");
                                });;
                        });
                    }
                })
                ->addColumn('name', function ($row) {
                    return $row->address ? $row->address->name : 'Không có tên';
                })
                ->addColumn('phone', function ($row) {
                    return $row->address ? $row->address->phone : 'Không có số điện thoại';
                })
                ->addColumn('discount', function ($row) {
                    if ($row->coupon_discount_amount <= 100) {
                        return $row->coupon_discount_amount . '%';
                    } else {
                        return format_cash($row->coupon_discount_amount);
                    }
                })
                ->addColumn('order_date', function ($row) {
                    return Carbon::parse($row->order_date)->format('d-m-Y H:i:s');
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('admin.orders.editOrders', $row->id) . '" class="me-2 btn btn-sm btn-primary">Xem</a>';
                })
                ->make(true);
            return $data;
        }
        return view('admin.orders.index');
    }
}
