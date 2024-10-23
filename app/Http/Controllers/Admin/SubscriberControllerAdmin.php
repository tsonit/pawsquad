<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\ServicesRequestAdmin;
use App\Models\EmailContent;
use App\Models\Service;
use App\Models\Subscriber;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Intervention\Image\Laravel\Facades\Image;

class SubscriberControllerAdmin extends Controller
{
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $subscribers = Subscriber::select(['id', 'fullname', 'email', 'status']);

            $data = DataTables::of($subscribers)
                ->filter(function ($query) use ($request) {
                    if ($request->has('search') && !empty($request->get('search')['value'])) {
                        $search = $request->get('search')['value'];
                        $query->where(function ($q) use ($search) {
                            $q->where('id', $search)
                                ->orWhere('fullname', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                    }
                })
                ->addColumn('action', function ($row) {
                    return ' <a href="' . route('admin.subcribers.deleteSubscriber', $row->id) . '" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">Xóa</a>';
                })
                ->make(true);
            return $data;
        }
        return view('admin.subscribers.index');
    }
    public function theme(Request $request)
    {
        if ($request->isMethod('post')) {
            $email = EmailContent::where('type', 1)->select(['id', 'email_type', 'content', 'created_at']);
            $data = DataTables::of($email)
                ->filter(function ($query) use ($request) {
                    if ($request->has('search') && !empty($request->get('search')['value'])) {
                        $search = $request->get('search')['value'];
                        $query->where(function ($q) use ($search) {
                            $q->where('id', $search)
                                ->orWhere('email_type', 'like', "%{$search}%");
                        });
                    }
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at->format('d-m-Y H:i:s');
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('admin.subscribers.editSubscriber', $row->id) . '" class="btn btn-sm btn-primary me-1">Sửa</a>'
                        . '<a href="' . route('admin.subscribers.editSubscriberTheme', $row->id) . '" class="btn btn-sm btn-info me-1">Sửa GD</a>'
                        . '<a href="' . route('admin.subscribers.deleteSubscriber', $row->id) . '" class="btn btn-sm btn-danger ">Xoá</a>';
                })
                ->make(true);
            return $data;
        }
        return view('admin.subscribers.index_theme');
    }
    public function add()
    {
        return view('admin.subscribers.add');
    }
    public function postAdd(Request $request)
    {
        try {
            $slug = Str::slug($request->email_type, '-');
            $email_type = generateSlug($slug, 'subscriber');
            EmailContent::create([
                'email_type' => $email_type,
                'type' => 1,
                'content' => contentDefaultSubscriber(),
            ]);
            return redirect(route('admin.subscribers.theme'))->withSuccessMessage('Thêm chiến dịch thành công!');
        } catch (\Exception $e) {
            return redirect(route('admin.subscribers.addSubscriber'))->withErrorMessage('Đã xảy ra lỗi khi thêm chiến dịch.');
        }
    }
    public function edit($id)
    {
        $data = EmailContent::where('type', 1)->find($id);
        if (!$data) {
            return redirect(route('admin.subscribers.theme'))->withErrorMessage('Không tìm thấy chiến dịch.');
        }
        return view('admin.subscribers.edit', compact('data'));
    }

    public function postEdit(Request $request, $id)
    {
        $data = EmailContent::where('type', 1)->find($id);
        if (!$data) {
            return redirect(route('admin.subscribers.theme'))->withErrorMessage('Không tìm thấy chiến dịch.');
        }
        if ($data->id == Service::defaultService()->id) {
            return redirect()->back()->withErrorMessage('Không thể sửa chiến dịch này.');
        }

        try {
            $slug = Str::slug($request->email_type, '-');
            $email_type = generateSlug($slug, 'subscriber', $data->id);
            $data->update([
                'email_type' => $email_type,
                'type' => 1,
                'content' => contentDefaultSubscriber(),
            ]);
            return redirect(route('admin.subscribers.theme'))->withSuccessMessage('Cập nhật chiến dịch thành công!');
        } catch (\Exception $e) {
            return redirect(route('admin.subscribers.editSubscriber', ['id' => encrypt($id)]))->withErrorMessage('Đã xảy ra lỗi khi cập nhật chiến dịch.');
        }
    }
    public function delete()
    {
        $id = request()->id;
        $subscriber = EmailContent::where('type', 1)->find($id);

        if ($subscriber == NULL) {
            return redirect(route('admin.subscribers.theme'))->withErrorMessage('Không tìm thấy chiến dịch.');
        }
        $subscriber->delete();
        return redirect(route('admin.subscribers.theme'))->withSuccessMessage('Đã xóa chiến dịch thành công.');
    }
    public function editTheme($id)
    {
        $data = EmailContent::where('type', 1)->find($id);
        if (!$data) {
            return redirect(route('admin.subscribers.theme'))->withErrorMessage('Không tìm thấy chiến dịch.');
        }
        return view('admin.subscribers.builder', compact('data'));
    }

    public function postEditTheme(Request $request, $id)
    {
        $data = EmailContent::where('type', 1)->find($id);
        if (!$data) {
            return response()->json([
                'message' => "Không tìm thấy ID chiến dịch"
            ], 404);
        }
        $data->update([
            'content' => $request->input('content'),
        ]);
        return response()->json([
            'success' => "Lưu thành công"
        ], 200);
    }
    public function getTheme($id)
    {
        $data = EmailContent::where('type', 1)->find($id);
        if (!$data) {
            return redirect(route('admin.subscribers.theme'))->withErrorMessage('Không tìm thấy chiến dịch.');
        }
        return view('admin.subscribers.theme', compact('data'));
    }
    public function uploadAsset(Request $request)
    {

        $templateID = $request->input('template_id');

        $path = "assets/builderjs/theme/subscribers/{$templateID}";
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
        return response()->json(['url' => asset("assets/builderjs/theme/subscribers/{$templateID}/{$filename}")], 200);
    }

    private function sanitizeFilename($filename)
    {
        return preg_replace('/[^a-z0-9\._\-]+/i', '_', $filename);
    }
}
