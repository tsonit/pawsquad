<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServicesRequestAdmin;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Intervention\Image\Laravel\Facades\Image;

class ServiceControllerAdmin extends Controller
{
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $services = Service::select(['id', 'name', 'status']);

            $data = DataTables::of($services)
                ->filter(function ($query) use ($request) {
                    if ($request->has('search') && !empty($request->get('search')['value'])) {
                        $search = $request->get('search')['value'];
                        $query->where(function ($q) use ($search) {
                            $q->where('id', $search)
                                ->orWhere('name', 'like', "%{$search}%");
                        });
                    }
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('admin.services.editService', $row->id) . '" class="btn btn-sm btn-primary me-1">Sửa</a>'
                        . '<a href="' . route('admin.services.editServiceTheme', $row->id) . '" class="btn btn-sm btn-info">Sửa GD</a>'
                        . ' <a href="' . route('admin.services.deleteService', $row->id) . '" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">Xóa</a>';
                })
                ->make(true);
            return $data;
        }
        return view('admin.services.index');
    }
    public function trashed(Request $request)
    {
        if ($request->isMethod('post')) {
            $services = Service::onlyTrashed()->select(['id', 'name', 'status']);

            $data = DataTables::of($services)
                ->filter(function ($query) use ($request) {
                    if ($request->has('search') && !empty($request->get('search')['value'])) {
                        $search = $request->get('search')['value'];
                        $query->where(function ($q) use ($search) {
                            $q->where('id', $search)
                                ->orWhere('name', 'like', "%{$search}%");
                        });
                    }
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('admin.services.editService', $row->id) . '" class="btn btn-sm btn-primary me-1">Sửa</a>'
                        . '<a href="' . route('admin.services.editServiceTheme', $row->id) . '" class="btn btn-sm btn-info">Sửa GD</a>'
                        . ' <a href="' . route('admin.services.restoreService', $row->id) . '" class="btn btn-sm btn-success restore-btn" data-id="' . $row->id . '">Khôi phục</a>'
                        . ' <a href="' . route('admin.services.deleteService', $row->id) . '" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">Xóa</a>';
                })
                ->make(true);
            return $data;
        }
        request()->session()->forget('uploadImages');
        return view('admin.services.trashed');
    }
    public function add()
    {
        request()->session()->forget('uploadImages');
        return view('admin.services.add');
    }
    public function postAdd(ServicesRequestAdmin $request)
    {
        if (session()->has('uploadImages')) {
            $status = $request->status == 'on' ? 1 : 0;
            $slug = generateSlug($request->slug, 'service');
            $uploadImages = session('uploadImages');
            foreach ($uploadImages as $image) {
                if ($image['type'] === 'avatar') {
                    $image['image'] = decrypt($image['image']);
                    $avatar = $image['image'];
                }
            }
            if (!$avatar) {
                return redirect(route('admin.services.addService'))->withErrorMessage('Vui lòng upload hình ảnh!');
            }
            try {

                $addService = Service::create([
                    'image' => $avatar,
                    'name' => $request->input('name'),
                    'slug' => $slug,
                    'status' => $status,
                    'short_description' => $request->input('short_description'),
                    'meta_title' => $request->input('meta_title') ?? NULL,
                    'meta_description' => $request->input('meta_description') ?? NULL,
                    'html_content' => contentDefaultService(),
                ]);
                return redirect(route('admin.services.index'))->withSuccessMessage('Thêm dịch vụ thành công!');
            } catch (\Exception $e) {
                return redirect(route('admin.services.addService'))->withErrorMessage('Đã xảy ra lỗi khi thêm dịch vụ.');
            }
        } else {
            return redirect(route('admin.services.addService'))->withErrorMessage('Vui lòng upload hình ảnh!');
        }
    }
    public function edit($id)
    {
        $data = Service::find($id);
        if (!$data) {
            return redirect(route('admin.services.index'))->withErrorMessage('Không tìm thấy dịch vụ.');
        }
        if ($data->id == Service::defaultService()->id) {
            return redirect()->back()->withErrorMessage('Không thể sửa dịch vụ này.');
        }
        request()->session()->put('service', [
            'id' => encrypt($id),
            'image' => encrypt($data->image),
        ]);
        request()->session()->forget('uploadImages');
        return view('admin.services.edit', compact('data'));
    }

    public function postEdit(ServicesRequestAdmin $request, $id)
    {
        $data = Service::find($id);
        if (!$data) {
            return redirect(route('admin.services.index'))->withErrorMessage('Không tìm thấy dịch vụ.');
        }
        if ($data->id == Service::defaultService()->id) {
            return redirect()->back()->withErrorMessage('Không thể sửa dịch vụ này.');
        }
        $uploadImage = session('uploadImages');
        $service = session('service.image');
        if (request()->session()->has('uploadImages')) {
            $image = decrypt($uploadImage[0]['image']);
        } else {
            $image = decrypt($service);
        }
        if (!$image) {
            return redirect()->back()->withErrorMessage('Vui lòng tải ảnh lên.');
        }
        try {
            $slug = generateSlug($request->slug, 'service', $data->id);
            $status = $request->status == 'on' ? 1 : 0;
            $data->update([
                'image' => $image,
                'name' => $request->input('name'),
                'slug' => $slug,
                'status' => $status,
                'short_description' => $request->input('short_description'),
                'meta_title' => $request->input('meta_title') ?? NULL,
                'meta_description' => $request->input('meta_description') ?? NULL,
            ]);
            return redirect(route('admin.services.index'))->withSuccessMessage('Cập nhật dịch vụ thành công!');
        } catch (\Exception $e) {
            return redirect(route('admin.services.editService', ['id' => encrypt($id)]))->withErrorMessage('Đã xảy ra lỗi khi cập nhật dịch vụ.');
        }
    }
    public function restore($id)
    {
        $service = Service::onlyTrashed()->find($id);
        if (!$service) {
            return redirect()->route('admin.services.index')->withErrorMessage('Dịch vụ không tìm thấy.');
        }
        $service->restore();
        return redirect()->route('admin.services.index')->withSuccessMessage('Khôi phục dịch vụ thành công.');
    }
    public function delete()
    {
        $id = request()->id;
        $service = Service::withTrashed()->find($id);

        if ($service == NULL) {
            return redirect(route('admin.services.index'))->withErrorMessage('Không tìm thấy dịch vụ.');
        }
        if ($service->id == Service::defaultService()->id) {
            return redirect()->back()->withErrorMessage('Không thể xoá dịch vụ này.');
        }
        if ($service->deleted_at) {
            if ($service->image) {
                deleteImages($service->image);
            }
            $service->forceDelete();
            return redirect(route('admin.services.index'))->withSuccessMessage('Đã xóa hoàn toàn dịch vụ.');
        } else {
            $service->delete();
            return redirect(route('admin.services.index'))->withSuccessMessage('Đã xóa dịch vụ, có thể khôi phục.');
        }
    }
    public function editTheme($id)
    {
        $data = Service::find($id);
        if (!$data) {
            return redirect(route('admin.services.index'))->withErrorMessage('Không tìm thấy dịch vụ.');
        }
        if ($data->id == Service::defaultService()->id) {
            return redirect()->back()->withErrorMessage('Không thể sửa dịch vụ này.');
        }
        return view('admin.services.builder', compact('data'));
    }

    public function postEditTheme(Request $request, $id)
    {
        $data = Service::find($id);
        if (!$data) {
            return response()->json([
                'message' => "Không tìm thấy ID dịch vụ"
            ], 404);
        }
        if ($data->id == Service::defaultService()->id) {
            return redirect()->back()->withErrorMessage('Không thể sửa dịch vụ này.');
        }
        $data->update([
            'html_content' => $request->input('content'),
        ]);
        return response()->json([
            'success' => "Lưu thành công"
        ], 200);
    }
    public function getTheme($id)
    {
        $data = Service::find($id);
        if (!$data) {
            return redirect(route('admin.services.index'))->withErrorMessage('Không tìm thấy dịch vụ.');
        }
        if ($data->id == Service::defaultService()->id) {
            return redirect()->back()->withErrorMessage('Không thể xem dịch vụ này.');
        }
        return view('admin.services.theme', compact('data'));
    }
    public function uploadAsset(Request $request)
    {

        $templateID = $request->input('template_id');

        $path = "assets/builderjs/theme/services/{$templateID}";
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        if ($request->input('assetType') === 'upload') {
            $file = $request->file('file');
            $filename = $this->sanitizeFilename($file->getClientOriginalName());
            $webpFilename = pathinfo($filename, PATHINFO_FILENAME) . '.webp';
            $webpPath = "{$path}/{$webpFilename}";
            $img = Image::read($file->getRealPath());
            $img->save($webpPath);
            $filename = $webpFilename;
        } elseif ($request->input('assetType') === 'url') {
            $filename = Str::random(10) . '.webp';
            $filepath = "{$path}/{$filename}";
            $content = file_get_contents($request->input('url'));
            $img = Image::read($content);
            $img->save($filepath);
        } elseif ($request->input('assetType') === 'base64') {
            $filename = Str::random(10) . '.png';
            $filepath = "{$path}/{$filename}";
            $base64Image = base64_decode($request->input('url_base64'));
            file_put_contents($filepath, $base64Image);
        } elseif ($request->input('assetType') === 'audio') {
            $file = $request->file('file');
            $filename = $this->sanitizeFilename($file->getClientOriginalName());
            $filepath = $file->storeAs($path, $filename, 'public');
        }
        return response()->json(['url' => asset("assets/builderjs/theme/services/{$templateID}/{$filename}")], 200);
    }

    private function sanitizeFilename($filename)
    {
        return preg_replace('/[^a-z0-9\._\-]+/i', '_', $filename);
    }
}
