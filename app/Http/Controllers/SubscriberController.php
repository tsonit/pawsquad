<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\SubscriberFormRequest;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SubscriberController extends Controller
{
    public function join(SubscriberFormRequest $request)
    {

        $existingSubscriber = Subscriber::where('email', $request->email_subscriber)->first();
        if ($existingSubscriber) {
            return redirect()->back()->with(noti('Email này đã được đăng ký nhận bản tin.','error'));
        }
        $subscriber = Subscriber::create([
            'email' => $request->email_subscriber,
            'fullname' => $request->fullname_subscriber,
            'status' => 1,
        ]);
        if (!$subscriber) {
            return redirect()->back()->with(noti('Đã xảy ra lỗi khi đăng ký nhận bản tin', 'error'));
        }
        $error = Helper::throttle('subscriber', 1, 10, 'mi');
        if ($error) {
            throw ValidationException::withMessages([
                'email_subscriber' => 'Vui lòng thử lại sau 10 phút',
            ]);
        }

        return redirect()->back()->with(noti('Đăng ký nhận bản tin thành công', 'success'));
    }
}
