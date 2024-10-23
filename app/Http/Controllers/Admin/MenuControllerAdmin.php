<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Service;
use Illuminate\Http\Request;

class MenuControllerAdmin extends Controller
{
    public function index(Request $request){
        $categories = Category::where('status',1)->get();
        $services = Service::where('status',1)->get();
        return view('admin.menu.index',compact('categories','services'));
    }
    public function save(Request $request)
    {
        $request->validate([
            'menu' => 'required|array',
        ]);

        $menuData = $request->input('menu');

        Menu::truncate(); // Xoá toàn bộ bản ghi cũ

        foreach ($menuData as $index => $item) {
            $this->saveMenuItem($item, null, $index); // Gọi hàm để lưu item
        }

        // Trả về phản hồi JSON
        return response()->json(['success' => true, 'message' => 'Lưu menu thành công']);
    }

    private function saveMenuItem($item, $parentId, $order)
    {
        // Lưu menu item
        $menu = Menu::create([
            'text' => $item['text'],
            'url' => $item['url'],
            'parent_id' => $parentId,
            'order' => $order,
            'depth' => $parentId ? 1 : 0, // Độ sâu có thể được điều chỉnh nếu cần
        ]);

        // Kiểm tra xem có children hay không và lưu chúng
        if (!empty($item['children'])) {
            foreach ($item['children'] as $index => $child) {
                $this->saveMenuItem($child, $menu->id, $index); // Gọi đệ quy để lưu child
            }
        }
    }
}
