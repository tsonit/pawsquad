<?php

namespace App\Http\Controllers;

use App\Models\Service;
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
}
