<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Option;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class OptionControllerAdmin extends Controller
{
    public function index()
    {
        $data = Option::get();
        request()->session()->put('logo', [
            'image' => getOption('DB_LOGO_URL') ? encrypt(getOption('DB_LOGO_URL')) : '',
        ]);
        return view('admin.option.index', compact('data'));
    }
    public function postEditOption(Request $request)
    {
        $uploadImage = session('uploadImages');
        $logoImage = session('logo.image');
        $faviconImage = session('favicon.image'); // Thêm favicon

        if (request()->session()->has('uploadImages')) {
            $image = decrypt($uploadImage[0]['image']);
        } else {
            $image = decrypt($logoImage);
        }
        $envValues = [
            'APP_URL' => $request->input('APP_URL'),
            'APP_NAME' => $request->input('APP_NAME'),
            'MAIL_MAILER' => $request->input('MAIL_MAILER'),
            'MAIL_HOST' => $request->input('MAIL_HOST'),
            'MAIL_PORT' => $request->input('MAIL_PORT'),
            'MAIL_USERNAME' => $request->input('MAIL_USERNAME'),
            'MAIL_PASSWORD' => $request->input('MAIL_PASSWORD'),
            'MAIL_ENCRYPTION' => $request->input('MAIL_ENCRYPTION'),
            'MAIL_FROM_ADDRESS' => $request->input('MAIL_FROM_ADDRESS'),
            'MAIL_FROM_NAME' => $request->input('MAIL_FROM_NAME'),
            'DB_LOGO_URL' => $image,
            'DB_PHONE' => $request->input('DB_PHONE'),
            'DB_EMAIL' => $request->input('DB_EMAIL'),
            'DB_ADDRESS_BRANCH' => $request->input('DB_ADDRESS_BRANCH'),
            'DB_ADDRESS' => $request->input('DB_ADDRESS'),
            'DB_IDAPP_FACEBOOK' => $request->input('DB_IDAPP_FACEBOOK'),
            'DB_SOCIAL_LINK_FACEBOOK' => $request->input('DB_SOCIAL_LINK_FACEBOOK'),
            'DB_SOCIAL_LINK_YOUTUBE' => $request->input('DB_SOCIAL_LINK_YOUTUBE'),
            'DB_SOCIAL_LINK_TIKTOK' => $request->input('DB_SOCIAL_LINK_TIKTOK'),
            'DB_SHORT_STANDOUT_NOTIFICATION' => $request->input('DB_SHORT_STANDOUT_NOTIFICATION'),
            'NOCAPTCHA_SECRET' => $request->input('NOCAPTCHA_SECRET'),
            'NOCAPTCHA_SITEKEY' => $request->input('NOCAPTCHA_SITEKEY'),
        ];
        foreach ($envValues as $key => $value) {
            if (substr($key, 0, 3) === 'DB_') {
                Option::updateOrCreate(['name' => $key], ['value' => $value]);
            } else {
                $envFilePath = base_path('.env');
                $envContent = File::get($envFilePath);
                $envContent = preg_replace("/^{$key}=.*/m", "{$key}=\"{$value}\"", $envContent);
                File::put($envFilePath, $envContent);
            }
        }

        return redirect(route('admin.options.index'))->withSuccessMessage('Sửa cài đặt thành công!');
    }
}
