<?php

use App\Models\Article;
use App\Models\Attribute;
use App\Models\AttributeSet;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Service;
use App\Models\Variations;
use App\Models\VariationValues;
use App\Models\Voucher;
use App\Models\VoucherType;
use App\Models\VoucherUsage;
use Artesaos\SEOTools\Facades\SEOTools;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

function checkCaptcha(Request $request)
{
    if (getOption('DB_TURN_CAPTCHA_CLOUDFLARE') == 1) {
        $turnstileResponse = $request->input('cf-turnstile-response');
        $response = Http::post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            'secret' => getOption('DB_SECRET_KEY_CAPTCHA_CLOUDFLARE'),
            'response' => $turnstileResponse,
        ]);
        $result = $response->json();
        if (!$result['success']) {
            return back()->with(noti('Xác minh captcha thất bại', 'error'));
        }
    }
    if (getOption('DB_TURN_CAPTCHA_GOOGLE') == 1) {
        $recaptchaResponse = $request->input('g-recaptcha-response');
        $response = Http::post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => getOption('DB_SECRET_KEY_CAPTCHA_GOOGLE'),
            'response' => $recaptchaResponse,
        ]);

        $result = $response->json();

        if (!$result['success']) {
            return back()->with(noti('Xác minh captcha thất bại', 'error'));
        }
    }
    return null;
}

function nameRole($role)
{
    switch ($role) {
        case 10:
            return 'ADMIN';
        case 9:
            return 'STAFF';
        case 1:
            return 'CTV';
        case 0:
            return 'Người dùng';
        case 'guest':
            return 'Khách';
        default:
            return 'Vai trò không xác định';
    }
}

function format_date($data)
{
    return \Carbon\Carbon::parse($data)->format('d/m/Y H:i:s');
}
function format_cash($price, $shorten = false)
{
    if ($shorten) {
        if ($price >= 1_000_000) {
            return number_format($price / 1_000_000, 0) . 'M'; // triệu
        } elseif ($price >= 1_000) {
            return number_format($price / 1_000, 0) . 'K'; // ngàn
        } elseif ($price >= 0) {
            return number_format($price, 0, ',', '.') . 'đ'; // đồng
        }
    }

    // Định dạng đầy đủ
    return number_format($price, 0, ',', '.') . 'đ'; // Đơn vị VND
}
function generateSlug($string, $type, $id = null)
{
    $slug = Str::lower(Str::slug($string, '-'));
    $count = 1;

    switch ($type) {
        case 'category':
            while (Category::select('slug')
                ->where('slug', $slug)
                ->where('id', '!=', $id) // Bỏ qua nếu ID hiện tại trùng
                ->exists()
            ) {
                $slug = $slug . '-' . $count;
                $count++;
            }
            break;
        case 'attributeset':
            while (AttributeSet::select('slug')
                ->where('slug', $slug)
                ->where('id', '!=', $id)
                ->exists()
            ) {
                $slug = $slug . '-' . $count;
                $count++;
            }
            break;
        case 'attribute':
            while (Attribute::select('slug')
                ->where('slug', $slug)
                ->where('id', '!=', $id)
                ->exists()
            ) {
                $slug = $slug . '-' . $count;
                $count++;
            }
            break;
        case 'brand':
            while (Brand::select('slug')
                ->where('slug', $slug)
                ->where('id', '!=', $id)
                ->exists()
            ) {
                $slug = $slug . '-' . $count;
                $count++;
            }
            break;
        case 'product':
            while (Product::select('slug')
                ->where('slug', $slug)
                ->where('id', '!=', $id)
                ->exists()
            ) {
                $slug = $slug . '-' . $count;
                $count++;
            }
            break;
        case 'service':
            while (Service::select('slug')
                ->where('slug', $slug)
                ->where('id', '!=', $id)
                ->exists()
            ) {
                $slug = $slug . '-' . $count;
                $count++;
            }
            break;
        case 'article':
            while (Article::select('slug')
                ->where('slug', $slug)
                ->where('id', '!=', $id)
                ->exists()
            ) {
                $slug = $slug . '-' . $count;
                $count++;
            }
            break;
        case 'blog':
            while (Blog::select('slug')
                ->where('slug', $slug)
                ->where('id', '!=', $id)
                ->exists()
            ) {
                $slug = $slug . '-' . $count;
                $count++;
            }
            break;
        default:
            return null;
    }

    return $slug;
}

function deleteImages($imagePaths)
{
    // Kiểm tra xem biến $imagePaths có phải là một mảng hay không
    if (is_array($imagePaths)) {
        foreach ($imagePaths as $path) {
            // Kiểm tra nếu $path là một mảng, ta sẽ lấy giá trị đầu tiên của nó
            if (is_array($path)) {
                $path = $path[0]; // Lấy đường dẫn ảnh từ mảng con
            }

            $basepath = base_path($path);
            // Xóa từng ảnh
            if (file_exists($basepath)) {
                unlink($basepath);
            }
        }
    } else {
        $basepath = base_path($imagePaths);
        // Xóa một ảnh
        if (file_exists($basepath)) {
            unlink($basepath);
        }
    }
}

function generateVariationOptions($options, $withTrash = true)
{

    if (count($options) == 0) {
        return $options;
    }
    $variation_ids = array();
    foreach ($options as $option) {

        $value_ids = array();
        if (isset($variation_ids[$option->variation_id])) {
            $value_ids = $variation_ids[$option->variation_id];
        }
        if (!in_array($option->variation_value_id, $value_ids)) {
            array_push($value_ids, $option->variation_value_id);
        }
        $variation_ids[$option->variation_id] = $value_ids;
    }
    $options = array();
    foreach ($variation_ids as $id => $values) {
        $variationValues = array();
        foreach ($values as $value) {
            $variationValue = VariationValues::find($value);
            $val = array(
                'id'   => $value,
                'name' => $variationValue->name,
                'code' => $variationValue->color_code
            );
            array_push($variationValues, $val);
        }
        $data['id'] = $id;

        if ($withTrash == true) {
            $data['name'] = Variations::withTrashed()->find($id)->name;
        } else {
            $data['name'] = Variations::find($id) ? Variations::find($id)->name : null;
        }
        if ($data['name']) {
            $data['values'] = $variationValues;
        } else {
            $data['values'] = $variationValues;
        }

        array_push($options, $data);
    }
    return $options;
}
function mergeImageWithList($image, $imageList)
{
    $decodedImageList = json_decode($imageList, true);

    // Kiểm tra nếu giải mã không thành công
    if (json_last_error() !== JSON_ERROR_NONE) {
        return $imageList; // Trả về danh sách gốc nếu có lỗi
    }

    $found = false;

    foreach ($decodedImageList as $item) {
        // Kiểm tra nếu hình ảnh đã tồn tại trong một trong các mảng con
        if (in_array($image, $item)) {
            $found = true;
            break;
        }
    }

    // Nếu chưa tồn tại trong danh sách, thêm hình ảnh vào đầu mảng
    if (!$found) {
        array_unshift($decodedImageList, [$image]);
    }

    return $decodedImageList;
}
function removeCoupon()
{
    if (request()->hasCookie('coupon_code')) {
        $response = new Response();
        Cookie::queue(Cookie::forget('coupon_code'));
        Cookie::queue(Cookie::forget('coupon_data'));
        return $response;
    }
}


function setCoupon($coupon)
{
    return Cookie::queue('coupon_code', $coupon->name, 60 * 24, '/');
}

function setCouponTypeDiscount($amount, $type)
{
    $formattedAmount = '';
    if ($type === 'flat') {
        $formattedAmount = format_cash($amount);
    } else {
        $formattedAmount = $amount . '%';
    }
    return Cookie::queue('coupon_data', $formattedAmount, 60 * 24, '/');
}

function getCoupon($type = "default")
{
    if ($type == "default") {
        if (request()->hasHeader("Coupon-Code")) {
            return request()->header("Coupon-Code");
        }
        if (request()->hasCookie('coupon_code')) {
            return request()->cookie('coupon_code');
        }
    }
    if ($type == "coupon_data") {
        if (request()->hasCookie('coupon_data')) {
            return request()->cookie('coupon_data');
        }
    }

    return '';
}


function checkCouponValidityForCheckout($carts)
{
    if (getCoupon() != '') {
        $voucherType = VoucherType::where('name', getCoupon())->first();
        if ($voucherType) {
            $voucher = Voucher::where('voucher_type_id', $voucherType->id)->first();
            $currentDateTime = Carbon::now()->toDateTimeString();
            if ($voucher->voucher_quantity <= 0) {
                removeCoupon();
                return [
                    'status'    => false,
                    'message'   => 'Mã giảm giá đã hết lượt'
                ];
            }
            # total coupon usage
            $totalCouponUsage = VoucherUsage::where('name', $voucherType->name)->sum('usage_count');
            if ($totalCouponUsage > $voucherType->customer_usage_limit) {
                # coupon usage limit reached
                removeCoupon();
                return [
                    'status'    => false,
                    'message'   => 'Mã giảm giá đã sử dụng'
                ];
            }

            if ($voucher->start_date > $currentDateTime) {
                removeCoupon();
                return [
                    'status'    => false,
                    'message'   => 'Mã giảm giá chưa đến thời gian bắt đầu'
                ];
            }
            if ($voucher->expired_date < $currentDateTime) {
                removeCoupon();
                return [
                    'status'    => false,
                    'message'   => 'Mã giảm giá đã hết hạn'
                ];
            }
            if ($voucher->start_date <= $currentDateTime && $voucher->expired_date >= $currentDateTime) {
                $subTotal = (float) getSubTotal($carts, false);
                if ($subTotal >= (float) $voucherType->min_spend) {
                    return [
                        'status'    => true,
                        'message'   => ''
                    ];
                } else {
                    removeCoupon();
                    return [
                        'status'    => false,
                        'message'   => 'Không đạt được số tiền tối thiểu để sử dụng phiếu giảm giá này'
                    ];
                }
            } else {
                removeCoupon();
                return [
                    'status'    => false,
                    'message'   => 'Mã giảm giá đã hết hạn hoặc chưa bắt đầu'
                ];
            }
        } else {
            removeCoupon();
            return [
                'status'    => false,
                'message'   => 'Mã giảm giá không tồn tại'
            ];
        }
    }

    return [
        'status'    => true,
        'message'   => ''
    ];
}
function getSubTotal($carts, $couponDiscount = true, $couponCode = '')
{
    $price = 0;
    $amount = 0;
    if (count($carts) > 0) {
        foreach ($carts as $cart) {
            $variation  = $cart->product_variation;
            $price += (float) $variation->price * $cart->quantity;
        }

        if ($couponDiscount) {
            $amount = getCouponDiscount($price, $couponCode);
        }
    }

    return $price - $amount;
}
function getCouponDiscount($subTotal, $code = '')
{
    $amount = 0;
    $voucherType = VoucherType::where('name', $code)->first();
    if ($voucherType) {
        $voucher = Voucher::where('voucher_type_id', $voucherType->id)->first();
        $currentDate = Carbon::now()->toDateString();
        # kiểm tra
        if ($voucher->start_date <= $currentDate && $voucher->expired_date >= $currentDate) {
            if ($voucherType->discount_type == 'flat') {
                $amount = $subTotal - (float) $voucherType->discount;
            } else {
                $amount = $subTotal - ((float) $voucherType->discount * $subTotal) / 100;
            }
            if ($amount < 0) {
                $amount = 0;
            }
        } else {
            removeCoupon();
        }
    } else {
        removeCoupon();
    }

    return $amount;
}
function formatAddress($address, $format = 'line')
{
    $village = isset($address->village) ? $address->village->name : '';
    $ward = isset($address->ward->name) ? $address->ward->name : '';
    $district = isset($address->district->name) ? $address->district->name : '';
    $province = isset($address->province->name) ? $address->province->name : '';
    $addressLine = isset($address->address) ? $address->address : '';

    if ($format == 'line') {
        return "{$addressLine} - {$village}, {$ward}, {$district}, {$province}";
    }
    if ($format === 'block') {
        $blockAddress = '';

        if ($addressLine) {
            $blockAddress .= "<address class='fs-sm mb-0'>{$addressLine}</address><br>";
        }
        if ($village) {
            $blockAddress .= "<strong>Thôn/Xóm: </strong>{$village}<br>";
        }
        if ($ward) {
            $blockAddress .= "<strong>Xã/Phường: </strong>{$ward}<br>";
        }
        if ($district) {
            $blockAddress .= "<strong>Quận/Huyện: </strong>{$district}<br>";
        }
        if ($province) {
            $blockAddress .= "<strong>Tỉnh/Thành phố: </strong>{$province}<br>";
        }

        return $blockAddress;
    }

    return '';
}
function getOrder($type, $user_id = NULL)
{
    // Kiểm tra trạng thái đơn hàng
    switch ($type) {
        case 'PAID':
            $orders = Order::where('order_status', 'PAID')
                ->where('shipment_status', 'DELIVERED');
            break;

        case 'CANCELED':
            $orders = Order::where('order_status', 'CANCELED');
            break;

        case 'PENDING':
            $orders = Order::where('order_status', 'PENDING')
                ->orWhere(function ($query) {
                    $query->where('order_status', 'PAID')
                        ->where('shipment_status', '!=', 'DELIVERED');
                });
            break;
        case 'ALL':
            $orders = Order::all();
            break;

        default:
            return null;
    }

    if ($user_id !== NULL) {
        $orders = $orders->where('user_id', $user_id);
    }

    return $orders->count();
}
function getService(){
    $service = Service::where('status',1)->get();
    return $service;
}
function contentDefaultService(){
    //Heredoc cho phép chèn các biến PHP vào trong chuỗi.
    $html = <<<HTML
    <!DOCTYPE html><html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no"><meta name="description" content=""><meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors"><meta name="generator" content="AcelleSystemLayouts"><title>Blank</title><base id="base-url" href="http://127.0.0.1:8000/assets/builderjs/templates/default/"><script>document.addEventListener("DOMContentLoaded",function(){var e=window.location.origin+"/assets/builderjs/templates/default/";document.getElementById("base-url").setAttribute("href",e)})</script><link href="css/bootstrap.min.css" rel="stylesheet"><link href="css/custom.css" rel="stylesheet"><style font-name="MS PMincho">@font-face{font-family:"MS PMincho";src:url(/assets/builderjs/font/ms-pmincho.ttf)}</style><style font-name="MS Mincho">@font-face{font-family:"MS Mincho";src:url(/assets/builderjs/font/ms-mincho.ttf)}</style><link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"><link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet"></head><body class="builderjs-layout" spellcheck="false"><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no"><meta name="description" content=""><meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors"><meta name="generator" content="AcelleSystemLayouts"><title>Blank</title><base id="base-url" href="http://127.0.0.1:8000/assets/builderjs/templates/default/"><script>document.addEventListener("DOMContentLoaded",function(){var e=window.location.origin+"/assets/builderjs/templates/default/";document.getElementById("base-url").setAttribute("href",e)})</script><link href="css/bootstrap.min.css" rel="stylesheet"><link href="css/custom.css" rel="stylesheet"><style font-name="MS PMincho">@font-face{font-family:"MS PMincho";src:url(/assets/builderjs/font/ms-pmincho.ttf)}</style><style font-name="MS Mincho">@font-face{font-family:"MS Mincho";src:url(/assets/builderjs/font/ms-mincho.ttf)}</style><link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"><link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet"><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no"><meta name="description" content=""><meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors"><meta name="generator" content="AcelleSystemLayouts"><title>Blank</title><base id="base-url" href="http://127.0.0.1:8000/assets/builderjs/templates/default/"><script>document.addEventListener("DOMContentLoaded",function(){var e=window.location.origin+"/assets/builderjs/templates/default/";document.getElementById("base-url").setAttribute("href",e)})</script><link href="css/bootstrap.min.css" rel="stylesheet"><link href="css/custom.css" rel="stylesheet"><style font-name="MS PMincho">@font-face{font-family:"MS PMincho";src:url(/assets/builderjs/font/ms-pmincho.ttf)}</style><style font-name="MS Mincho">@font-face{font-family:"MS Mincho";src:url(/assets/builderjs/font/ms-mincho.ttf)}</style><link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"><link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet"><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no"><meta name="description" content=""><meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors"><meta name="generator" content="AcelleSystemLayouts"><title>Blank</title><script>document.addEventListener("DOMContentLoaded",function(){var e=window.location.origin+"/assets/builderjs/templates/default/";document.getElementById("base-url").setAttribute("href",e)})</script><link href="css/bootstrap.min.css" rel="stylesheet"><link href="css/custom.css" rel="stylesheet"><style font-name="MS PMincho">@font-face{font-family:"MS PMincho";src:url(/assets/builderjs/font/ms-pmincho.ttf)}</style><style font-name="MS Mincho">@font-face{font-family:"MS Mincho";src:url(/assets/builderjs/font/ms-mincho.ttf)}</style><link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"><link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet"><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no"><meta name="description" content=""><meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors"><meta name="generator" content="AcelleSystemLayouts"><title>Blank</title><script>document.addEventListener("DOMContentLoaded",function(){var e=window.location.origin+"/assets/builderjs/templates/default/";document.getElementById("base-url").setAttribute("href",e)})</script><link href="css/bootstrap.min.css" rel="stylesheet"><link href="css/custom.css" rel="stylesheet"><style font-name="MS PMincho">@font-face{font-family:"MS PMincho";src:url(/assets/builderjs/font/ms-pmincho.ttf)}</style><style font-name="MS Mincho">@font-face{font-family:"MS Mincho";src:url(/assets/builderjs/font/ms-mincho.ttf)}</style><link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"><link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet"><div builder-element="PageElement" style="padding-top:50px;padding-bottom:50px" class=""><div builder-element="BlockElement" style="padding-top:0;padding-bottom:0;opacity:1" class=""><div class="container"><div id="copcb" builder-element="CellContainerElement" style="display:flex;flex-wrap:wrap" data-layout="5-5" class=""><div style="width:50%" builder-element="CellElement" class=""><div builder-element="BlockElement" style="padding-top:15px;padding-bottom:15px;opacity:1" class=""><div class="container"><div builder-element="TextElement" builder-inline-edit="" id="mce_5" style="position:relative;margin:10px" spellcheck="false"><div class="section-title1"><span><img src="/assets/clients/images/icon/section-vec-l1.svg" alt=""><span style="font-size:24pt;padding:5px!important">{NAME_SERVICE}</span><img src="/assets/clients/images/icon/section-vec-r1.svg" alt=""></span></div></div></div></div></div><div style="width:50%" builder-element="CellElement" class=""></div></div></div></div><div builder-element="BlockElement" style="padding-top:0;padding-bottom:0;opacity:1" class=""><div class="container"><div id="f9JSb" builder-element="CellContainerElement" style="display:flex;flex-wrap:wrap" data-layout="5-5" class="cell-responsive"><div style="width:50%" builder-element="CellElement" class=""><div builder-element="BlockElement" style="padding-top:15px;padding-bottom:15px;opacity:1" class=""><div class="container"><div builder-element="TextElement" builder-inline-edit="" id="mce_6" style="position:relative;margin:5px" spellcheck="false" class=""><span style="color:#faad14;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';font-size:25.6px;font-weight:600"><span style="color:#faad14;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';font-size:25.6px;font-weight:600"><span style="color:#f46f30">Tận tâm chăm sóc thú cưng</span></span></span><span style="color:#faad14;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';font-size:25.6px;font-weight:600;background-color:rgba(255,255,255,.9)"><span style="color:#faad14;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';font-size:25.6px;font-weight:600;background-color:rgba(255,255,255,.9)"><br></span></span><p style="margin-bottom:1.3em;-webkit-font-smoothing:antialiased;text-shadow:rgba(0,0,0,.004) 1px 1px 1px;text-rendering:optimizelegibility;color:#686868;font-size:medium;font-weight:400">Dịch vụ lưu trú thú cưng tận tâm tại PawSquad mang đến cho thú cưng của bạn một không gian thoải mái và an toàn với các ưu điểm vượt trội:</p><ul><li style="list-style-type:none"><ul style="list-style-position:initial;list-style-image:initial;padding:0;margin-bottom:1.3em;color:#686868;font-size:medium;font-weight:400"><li><strong>KHÔNG NHỐT CHUỒNG</strong>: Chúng tôi cam kết mang đến môi trường thoáng đãng, để thú cưng của bạn được tự do vui chơi và thư giãn.</li><li><strong>MÔI TRƯỜNG SẠCH SẼ, AN TOÀN</strong>: Khu vực lưu trú luôn được vệ sinh định kỳ, khử khuẩn kỹ lưỡng để bảo vệ thú cưng khỏi các nguy cơ bệnh tật.</li><li><strong>DỊCH VỤ ĐƯA ĐÓN TẬN NƠI</strong>: Chúng tôi cung cấp dịch vụ đưa đón tận nhà với chi phí hợp lý, giúp bạn tiết kiệm thời gian.</li><li><strong>CHĂM SÓC ĐẶC BIỆT</strong>: Với những thú cưng cần chế độ chăm sóc riêng, chúng tôi luôn sẵn sàng đáp ứng mọi yêu cầu để đảm bảo sức khỏe và sự thoải mái cho chúng.</li></ul></li></ul></div></div></div></div><div style="width:50%" builder-element="CellElement" class=""><div builder-element="BlockElement" style="padding-top:0;padding-bottom:0;opacity:1" class="" data-hide-on="mobile"><div class="container"><a href="#"><img builder-element="" class="" style="width:100%;height:auto" src="/assets/builderjs/theme/services/1/image-removebg-preview_2_.webp" alt="Dịch vụ lưu trú"></a></div></div></div></div></div></div><div builder-element="BlockElement" style="padding-top:15px;padding-bottom:15px;opacity:1" class=""><div class="container"><div builder-element="TextElement" builder-inline-edit="" id="mce_8" style="position:relative" spellcheck="false" class=""><span style="display:block;text-align:center"><span style="color:#f46f30;font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Helvetica,Arial,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol"><span style="font-size:25.6px"><b>Bảng giá dịch vụ lưu trú</b></span></span></span></div></div></div><div builder-element="BlockElement" style="padding-top:15px;padding-bottom:15px;opacity:1" class=""><div class="container"><div builder-element="TableBlockElement" class=""><div class="table-responsive"><table class="table"><thead><tr builder-element="" class=""><th><span builder-element="" builder-inline-edit="" id="mce_10" style="position:relative" spellcheck="false">Loại</span></th><th><span builder-element="" builder-inline-edit="" id="mce_11" style="position:relative" spellcheck="false" class="">Trọng lượng</span></th><th><span builder-element="" builder-inline-edit="" id="mce_12" style="position:relative" spellcheck="false" class="">Tiền</span></th></tr></thead><tbody><tr builder-element="" class=""><td><span builder-element="" builder-inline-edit="" id="mce_9" style="position:relative" spellcheck="false">Chó</span></td><td><span builder-element="" builder-inline-edit="" id="mce_13" style="position:relative" spellcheck="false"><span style="color:#000;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';font-size:medium;background-color:rgba(255,255,255,.9)">1kg – 10kg</span></span></td><td><span builder-element="" builder-inline-edit="" id="mce_14" style="position:relative" spellcheck="false" class="">75.000/ngày</span></td></tr><tr builder-element="" class=""><td><span builder-element="" builder-inline-edit="" id="mce_15" style="position:relative" spellcheck="false" class="">Chó</span></td><td><span builder-element="" builder-inline-edit="" id="mce_16" style="position:relative" spellcheck="false" class=""><span style="color:#000;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';font-size:medium;background-color:rgba(255,255,255,.9)">11kg – 20kg</span></span></td><td><span builder-element="" builder-inline-edit="" id="mce_17" style="position:relative" spellcheck="false" class=""><span style="color:#000;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';font-size:medium;background-color:rgba(255,255,255,.9)">105.000/ngày</span></span></td></tr><tr builder-element="" class=""><td><span builder-element="" builder-inline-edit="" id="mce_18" style="position:relative" spellcheck="false" class="">Chó</span></td><td><span builder-element="" builder-inline-edit="" id="mce_19" style="position:relative" spellcheck="false">trên 20kg</span></td><td><span builder-element="" builder-inline-edit="" id="mce_20" style="position:relative" spellcheck="false" class="">150.000/ngày</span></td></tr><tr builder-element="" class="" style=""><td><span builder-element="" builder-inline-edit="" id="mce_18" style="position:relative" spellcheck="false">Mèo</span></td><td><span builder-element="" builder-inline-edit="" id="mce_19" style="position:relative" spellcheck="false"><span style="color:#000">1kg - 10kg</span></span></td><td><span builder-element="" builder-inline-edit="" id="mce_20" style="position:relative" spellcheck="false">55.000/ngày</span></td></tr><tr builder-element="" class="" style=""><td><span builder-element="" builder-inline-edit="" id="mce_18" style="position:relative" spellcheck="false">Mèo</span></td><td><span builder-element="" builder-inline-edit="" id="mce_19" style="position:relative" spellcheck="false"><span style="color:#000">trên 10kg</span></span></td><td><span builder-element="" builder-inline-edit="" id="mce_20" style="position:relative" spellcheck="false">75.000/ngày</span></td></tr></tbody></table></div></div></div></div></div></body></html>
    HTML;
    return $html;
}
function getStatusBooking($type)
{
    switch ($type) {
        case 0:
            return 'Đã tạo';
        case 1:
            return 'Đang xử lý';
        case 2:
            return 'Hoàn thành';
        case 3:
            return 'Đã hủy';
        case 4:
            return 'Tạm hoãn';
        case 5:
            return 'Đã xác nhận';
        case 6:
            return 'Không thành công';
        default:
            return 'N/A';
    }
}

