<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    public function index(Request $request){
        if ($request->code != null) {
            $searchCode = $request->code;
            $searchCode = preg_replace('/[^0-9]/', '', $request->code);
            $order = Order::where('id', $searchCode)->where('user_id', auth()->user()->id)->first();

            if (!is_null($order)) {

                $view = view('clients.user.orderTrack', ['order' => $order, 'searchCode' => $searchCode]);
            } else {
                // flash('Không tìm thấy hoá đơn này')->error();
                $view = view('clients.user.orderTrack', ['searchCode' => $searchCode]);
            }
        } else {
            $view = view('clients.user.orderTrack');
        }

        return $view;
    }
}
