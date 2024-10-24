<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderUpdate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
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
    public function edit(Request $request,$id){
        $order = Order::with(['address','orderItems','user'])->find($id);
        if (!$order) {
            return redirect(route('admin.orders.index'))->withErrorMessage('Không tìm thấy hoá đơn.');
        }
        return view('admin.orders.edit',compact('order'));
    }

    public function downloadOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            $notification = array('message' => $validator->errors(), 'alert-type' => 'error');
            return redirect()->back()->with($notification);
        }

        try {
            if ($request->code != null) {
                $searchCode = preg_replace('/[^0-9]/', '', $request->code);
                $order = Order::with(['address', 'orderItems'])->where('id', $searchCode)
                    ->where('user_id', auth()->user()->id)
                    ->first();

                if (!$order) {
                    return redirect()->route('home')->with(noti('Không tìm thấy hoá đơn', 'error'));
                }

                $font_family = "'Roboto','sans-serif'";
                $pdf = Pdf::loadView('clients.checkout.downloadInvoice', [
                    'order' => $order,
                    'font_family' => $font_family,
                    'direction' => 'ltr',
                    'default_text_align' => 'left',
                    'reverse_text_align' => 'right'
                ]);
                // Trả về dữ liệu PDF dưới dạng phản hồi nhị phân
                return response($pdf->output(), 200)
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'attachment; filename="INV' . $order->id . '.pdf"');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'message' => 'Đã xảy ra lỗi: ' . $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }
    }
    public function updatePaymentStatus(Request $request)
    {
        $order = Order::findOrFail((int)$request->order_id);
        $order->order_status = $request->status;
        $order->save();
        if ($request->status == "PENDING") {
            $status = "chờ thanh toán";
        } else if ($request->status == 'PAID') {
            $status = "đã thanh toán";
        } else {
            $status = "đã huỷ";
        }
        OrderUpdate::create([
            'order_id' => $order->id,
            'user_id' => auth()->user()->id,
            'note' => 'Trạng thái thanh toán được cập nhật thành ' . $status . '.',
        ]);

        return true;
    }

    public function updateDeliveryStatus(Request $request)
    {
        $order = Order::findOrFail((int)$request->order_id);
        $order->shipment_status = $request->status;
        $order->save();
        if ($request->status == "ORDERPLACE") {
            $status = "đã được tạo";
        } else   if ($request->status == "PACKED") {
            $status = 'đã nhận và đang đóng gói';
        } else  if ($request->status == "SHIPPED") {
            $status = 'đã được vận chuyển';
        } else   if ($request->status == "INTRANSIT") {
            $status = 'đang trên đường đến điểm đến';
        } else   if ($request->status == "OUTFORDELIVERY") {
            $status = 'đang được giao cho người nhận';
        } else   if ($request->status == "DELIVERED") {
            $status = 'đã được giao hàng thành công';
        } else  if ($request->status == "DELAYED") {
            $status = 'đã bị trễ hẹn trong quá trình vận chuyển';
        } else  if ($request->status == "EXCEPTION") {
            $status = 'đã gặp vấn đề hoặc ngoại lệ trong quá trình vận chuyển';
        } else  if ($request->status == "RETURNED") {
            $status = 'đã được hoàn trả lại cho người gửi';
        }
        OrderUpdate::create([
            'order_id' => $order->id,
            'user_id' => auth()->user()->id,
            'note' => 'Đơn hàng ' . $status . '.',
        ]);

        return true;
    }
}
