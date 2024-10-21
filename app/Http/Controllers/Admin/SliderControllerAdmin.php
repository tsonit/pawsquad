<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SliderRequestAdmin;
use App\Models\Category;
use App\Models\Slider;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SliderControllerAdmin extends Controller
{
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $brands = Slider::select(['id', 'title', 'status', 'image', 'order']);

            $data = DataTables::of($brands)
                ->filter(function ($query) use ($request) {
                    if ($request->has('search') && !empty($request->get('search')['value'])) {
                        $search = $request->get('search')['value'];
                        $query->where(function ($q) use ($search) {
                            $q->where('id', $search)
                                ->orWhere('title', 'like', "%{$search}%");
                        });
                    }
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('admin.sliders.editSliders', $row->id) . '" class="btn btn-sm btn-primary">Sửa</a>'
                        . ' <a href="' . route('admin.sliders.deleteSliders', $row->id) . '" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">Xóa</a>';
                })
                ->make(true);
            return $data;
        }
        request()->session()->forget('uploadImages');
        return view('admin.sliders.index');
    }
    public function add()
    {
        $categories = Category::select('name', 'id')
            ->where('status', 1)
            ->get();
        request()->session()->forget('uploadImages');
        return view('admin.sliders.add', compact('categories'));
    }
    public function postAdd(SliderRequestAdmin $request)
    {
        if (session()->has('uploadImages')) {
            $status = $request->status == 'on' ? 1 : 0;
            $uploadImages = session('uploadImages');
            foreach ($uploadImages as $image) {
                if ($image['type'] === 'avatar') {
                    $image['image'] = decrypt($image['image']);
                    $avatar = $image['image'];
                }
            }
            if (!$avatar) {
                return redirect(route('admin.sliders.addSliders'))->withErrorMessage('Vui lòng upload hình ảnh!');
            }
            $order = $request->input('order');
            $existingOrder = Slider::where('order', $order)->first();
            if ($existingOrder) {
                return redirect(route('admin.sliders.addSliders'))->withErrorMessage('Thứ tự đã tồn tại. Vui lòng chọn thứ tự khác!');
            }

            try {
                $addSlider = Slider::create([
                    'category' => $request->input('category'),
                    'description' => $request->input('description'),
                    'title' => $request->input('title'),
                    'image' => $avatar,
                    'order' => $order,
                    'status' => $status,
                    'button_link_text' => $request->input('button_link_text'),
                    'button_text' => $request->input('button_text'),
                    'price' => $request->input('price'),
                ]);

                return redirect(route('admin.sliders.index'))->withSuccessMessage('Thêm slider thành công!');
            } catch (\Exception $e) {
                dd($e->getMessage());
                return redirect(route('admin.sliders.addSliders'))->withErrorMessage('Đã xảy ra lỗi khi thêm slider.');
            }
        } else {
            return redirect(route('admin.sliders.addSliders'))->withErrorMessage('Vui lòng upload hình ảnh!');
        }
    }
    public function edit($id)
    {
        $data = Slider::find($id);
        if (!$data) {
            return redirect(route('admin.sliders.index'))->withErrorMessage('Không tìm thấy slider.');
        }
        $categories = Category::select('name', 'id')
            ->where('status', 1)
            ->get();
        request()->session()->put('slider', [
            'id' => encrypt($id),
            'image' => encrypt($data->image),
        ]);
        request()->session()->forget('uploadImages');
        return view('admin.sliders.edit', compact('data','categories'));
    }

    public function postEdit(SliderRequestAdmin $request, $id)
    {
        $data = Slider::find($id);
        if (!$data) {
            return redirect(route('admin.sliders.index'))->withErrorMessage('Không tìm thấy slider.');
        }
        $uploadImage = session('uploadImages');
        $sliderImage = session('slider.image');
        if (request()->session()->has('uploadImages')) {
            $image = decrypt($uploadImage[0]['image']);
        } else {
            $image = decrypt($sliderImage);
        }
        if (!$image) {
            return redirect()->back()->withErrorMessage('Vui lòng tải ảnh lên.');
        }
        $order = $request->input('order');
        $existingOrder = Slider::where('order', $order)->where('id','!=',$id)->first();
        if ($existingOrder) {
            return redirect()->back()->withErrorMessage('Thứ tự đã tồn tại. Vui lòng chọn thứ tự khác!');
        }

        try {
            $status = $request->status == 'on' ? 1 : 0;
            $data->update([
                'category' => $request->input('category'),
                'description' => $request->input('description'),
                'title' => $request->input('title'),
                'image' => $image,
                'order' => $request->input('order'),
                'status' => $status,
                'button_link_text' => $request->input('button_link_text'),
                'button_text' => $request->input('button_text'),
                'price' => $request->input('price'),
            ]);
            return redirect(route('admin.sliders.index'))->withSuccessMessage('Cập nhật slider thành công!');
        } catch (\Exception $e) {
            return redirect(route('admin.sliders.editSliders', ['id' => encrypt($id)]))->withErrorMessage('Đã xảy ra lỗi khi cập nhật slider.');
        }
    }

    public function delete()
    {
        $id = request()->id;
        $slider = Slider::find($id);

        if ($slider == NULL) {
            return redirect(route('admin.sliders.index'))->withErrorMessage('Không tìm thấy slider.');
        }
        if ($slider->image) {
            deleteImages($slider->image);
        }
        $slider->delete();
        return redirect(route('admin.sliders.index'))->withSuccessMessage('Đã xóa slider thành công.');
    }
}
