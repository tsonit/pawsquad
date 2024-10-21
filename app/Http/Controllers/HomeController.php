<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(){
        $categories = Category::where('slug','!=','uncategorized')->orderBy('created_at','DESC')->IsParent()->IsActive()->take(8)->get();
        $products = Product::IsActive()->take(8)->orderBy('created_at','DESC')->get();
        $sliders = Slider::with('danhmuc')->where('status', 1)->orderBy('order', 'asc')->get();
        return view('clients.home.index',compact('categories','products','sliders'));
    }

}
