<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingFormRequest;
use App\Models\Contact;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Intervention\Image\Laravel\Facades\Image;

class ContactControllerAdmin extends Controller
{
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $booking = Contact::with('service')->select(['id', 'name', 'phone', 'email', 'message', 'service_id', 'scheduled_at', 'status']);
            if ($request->has('date_range') && !empty($request->get('date_range'))) {
                // Giả sử input là '2024-10-01 đến 2024-10-31'
                // Phân tách chuỗi thành 2 ngày
                $dates = explode(' đến ', $request->get('date_range'));
                if (count($dates) == 2) {
                    // Sử dụng Carbon để phân tích ngày tháng
                    $startDate = \Carbon\Carbon::createFromFormat('Y-m-d', trim($dates[0]))->startOfDay();
                    $endDate = \Carbon\Carbon::createFromFormat('Y-m-d', trim($dates[1]))->endOfDay();
                    // Áp dụng điều kiện chỉ cho cột scheduled_at
                    $booking->whereBetween('scheduled_at', [$startDate, $endDate]);
                }
            }

            $data = DataTables::of($booking)
                ->filter(function ($query) use ($request) {
                    if ($request->has('search') && !empty($request->get('search')['value'])) {
                        $search = $request->get('search')['value'];
                        $query->where(function ($q) use ($search) {
                            $q->where('id', $search)
                                ->orWhere('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
                        });
                    }
                })
                ->addColumn('service', function ($row) {
                    return $row->service ? limitText($row->service->name, 20) : 'N/A';
                })
                ->addColumn('scheduled', function ($row) {
                    return $row->scheduled_at ? Carbon::parse($row->scheduled_at)->format('d-m-Y H:i') : 'N/A';
                })
                ->addColumn('action', function ($row) {
                    return '<a onclick="showBookingModal(' . $row->id . ')" class="btn btn-sm btn-primary me-1 text-white">Sửa</a>'
                        . ' <a href="' . route('admin.booking.deleteBooking', $row->id) . '" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">Xóa</a>';
                })
                ->make(true);
            return $data;
        }
        return view('admin.booking.index');
    }
    public function trashed(Request $request)
    {
        if ($request->isMethod('post')) {
            $booking = Contact::onlyTrashed()->with('service')->select(['id', 'name', 'phone', 'email', 'message', 'service_id', 'scheduled_at', 'status']);
            if ($request->has('date_range') && !empty($request->get('date_range'))) {
                // Giả sử input là '2024-10-01 đến 2024-10-31'
                // Phân tách chuỗi thành 2 ngày
                $dates = explode(' đến ', $request->get('date_range'));
                if (count($dates) == 2) {
                    // Sử dụng Carbon để phân tích ngày tháng
                    $startDate = \Carbon\Carbon::createFromFormat('Y-m-d', trim($dates[0]))->startOfDay();
                    $endDate = \Carbon\Carbon::createFromFormat('Y-m-d', trim($dates[1]))->endOfDay();
                    // Áp dụng điều kiện chỉ cho cột scheduled_at
                    $booking->whereBetween('scheduled_at', [$startDate, $endDate]);
                }
            }

            $data = DataTables::of($booking)
                ->filter(function ($query) use ($request) {
                    if ($request->has('search') && !empty($request->get('search')['value'])) {
                        $search = $request->get('search')['value'];
                        $query->where(function ($q) use ($search) {
                            $q->where('id', $search)
                                ->orWhere('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
                        });
                    }
                })
                ->addColumn('service', function ($row) {
                    return $row->service ? limitText($row->service->name, 20) : 'N/A';
                })
                ->addColumn('scheduled', function ($row) {
                    return $row->scheduled_at ? Carbon::parse($row->scheduled_at)->format('d-m-Y H:i') : 'N/A';
                })
                ->addColumn('action', function ($row) {
                    return ' <a href="' . route('admin.booking.restoreBooking', $row->id) . '" class="btn btn-sm btn-success restore-btn" data-id="' . $row->id . '">Khôi phục</a>'
                        . ' <a href="' . route('admin.booking.deleteBooking', $row->id) . '" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">Xóa</a>';
                })
                ->make(true);
            return $data;
        }
        return view('admin.booking.trashed');
    }
    public function showInfo(Request $request)
    {
        $contact = Contact::findOrFail($request->id);
        $services = Service::where('status', 1)->get();
        return view('admin.booking.modal', ['booking' => $contact, 'services' => $services]);
    }
    public function postEdit(BookingFormRequest $request, $id)
    {
        $data = Contact::find($id);
        if (!$data) {
            return redirect(route('admin.booking.index'))->withErrorMessage('Không tìm thấy đặt lịch.');
        }
        try {
            $data->update([
                'name' => $request->input('name'),
                'phone' => $request->input('phone'),
                'email' => $request->input('email'),
                'message' => $request->input('message'),
                'status' => $request->input('status'),
                'scheduled_at' => Carbon::createFromFormat('d/m/Y H:i', $request->date)->format('Y-m-d H:i:s'),
                'service_id' => $request->input('service_id'),
                'note' => $request->input('note') ?? NULL,
            ]);
            return redirect(route('admin.booking.index'))->withSuccessMessage('Cập nhật đặt lịch thành công!');
        } catch (\Exception $e) {
            return redirect(route('admin.services.editService', ['id' => encrypt($id)]))->withErrorMessage('Đã xảy ra lỗi khi cập nhật đặt lịch.');
        }
    }
    public function restore($id)
    {
        $booking = Contact::onlyTrashed()->find($id);
        if (!$booking) {
            return redirect()->route('admin.booking.index')->withErrorMessage('đặt lịch không tìm thấy.');
        }
        $booking->restore();
        return redirect()->route('admin.booking.index')->withSuccessMessage('Khôi phục đặt lịch thành công.');
    }
    public function delete()
    {
        $id = request()->id;
        $booking = Contact::withTrashed()->find($id);

        if ($booking == NULL) {
            return redirect(route('admin.booking.index'))->withErrorMessage('Không tìm thấy đặt lịch.');
        }
        if ($booking->deleted_at) {
            $booking->forceDelete();
            return redirect(route('admin.booking.index'))->withSuccessMessage('Đã xóa hoàn toàn đặt lịch.');
        } else {
            $booking->delete();
            return redirect(route('admin.booking.index'))->withSuccessMessage('Đã xóa đặt lịch, có thể khôi phục.');
        }
    }
}
