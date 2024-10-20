<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\ServicesRequestAdmin;
use App\Http\Requests\BookingFormRequest;
use App\Models\Contact;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request){
        $data = Service::where('slug',$request->slug)->where('status',1)->first();
        if(!$data){
            return redirect()->back()->withErrorMessage('Không tìm thấy dịch vụ này.');
        }
        $content = $data->html_content;
        $replaceTags = [
            '{NAME_SERVICE}' => $data->name ?? NULL,
        ];
        $content = str_replace(array_keys($replaceTags), array_values($replaceTags), $content);
        return view('clients.service.index',compact('content','data'));
    }
    public function post(BookingFormRequest $request){
        try {
            $service = Service::where('slug',$request->service)->where('status',1)->first();
            if(!$service){
                return redirect()->back()->withErrorMessage('Chọn dịch vụ không hợp lệ.');
            }
            $scheduledAt = Carbon::createFromFormat('d/m/Y H:i', $request->input('date'))->format('Y-m-d H:i:s');
            Contact::create([
                'name' => $request->input('name'),
                'phone' => $request->input('phone'),
                'email' => $request->input('email'),
                'message' => $request->input('content'),
                'scheduled_at' => $scheduledAt,
                'service_id' => $service->id,
            ]);
            return redirect()->back()->withSuccessMessage('Đặt lịch thành công!');
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->withErrorMessage('Đã xảy ra lỗi khi đặt lịch.');
        }
    }
}
