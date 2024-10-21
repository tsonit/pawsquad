@extends('layouts.admin')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/quill/editor.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/dropzone/dropzone.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/tagify/tagify.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/shepherd/shepherd.css') }}" />
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="app-ecommerce">
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-1">Thêm slider</h4>
                    <a href="#" id="question" class="btn btn-link text-primary">
                        <i class="fas fa-question"></i>
                    </a>
                </div>

            </div>
            <form id="form-validate" method="POST">
                <div class="row ">
                    <div class="col-12 order-1">
                        <div class="card mb-6 ">
                            <div class="card-header">
                                <h5 class="card-tile mb-0">Thông tin</h5>

                            </div>
                            <div class="card-body">
                                <div class="mb-6">
                                    <label class="form-label" for="ecommerce-product-name">Tiêu đề chính</label>
                                    <input type="text" class="form-control" placeholder="Tiêu đề chính" name="title"
                                        id="title" aria-label="Tiêu đề chính" value="{{ old('title') }}" />
                                </div>
                                <div class="mb-6">
                                    <label class="form-label" for="ecommerce-product-name">Giá</label>
                                    <input type="text" class="form-control" placeholder="Giá" name="price"
                                        aria-label="Giá" value="{{ old('price') }}" />
                                </div>
                                <div class="mb-6">
                                    <label class="form-label mb-1" for="category"> Danh mục </label>
                                    <select id="category" class="select2 form-select" data-placeholder="Chọn danh mục"
                                        name="category">
                                        <option value="">Chọn danh mục</option>
                                        @forelse($categories as $row)
                                            <option value="{{ $row->id }}"
                                                {{ old('category', session('category')) == $row->id ? 'selected' : '' }}>
                                                {{ $row->name }}
                                            </option>
                                        @empty
                                            <option value="" disabled>Không có danh mục</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="form-group mt-3 mb-3">
                                    <label for="validationCustom05" class="col-form-label pt-0">Mô
                                        tả ngắn</label>
                                    <textarea class="form-control w-100" rows="4" cols="12" max="100" name="description">{{ old('description') }}</textarea>
                                </div>

                                <div class="align-items-center border-top pt-2 status">
                                    <span class="mb-0 me-3">Hiển thị</span>
                                    <label class="switch switch-square">
                                        <input type="checkbox" name="status" class="switch-input" checked
                                            {{ old('status', 1) ? 'checked' : '' }}>
                                        <span class="switch-toggle-slider">
                                            <span class="switch-on"><i class="ti ti-check"></i></span>
                                            <span class="switch-off"><i class="ti ti-x"></i></span>
                                        </span>
                                    </label>
                                </div>
                                <div class="mb-6">
                                    <label class="form-label" for="ecommerce-product-name">Nội dung nút</label>
                                    <input type="text" class="form-control" placeholder="Nội dung nút" name="button_text"
                                        aria-label="Nội dung nút" value="{{ old('button_text') }}" />
                                </div>
                                <div class="mb-6">
                                    <label class="form-label" for="ecommerce-product-name">Đường dẫn nút</label>
                                    <input type="text" class="form-control" placeholder="Đường dẫn nút"
                                        name="button_link_text" aria-label="Đường dẫn nút"
                                        value="{{ old('button_link_text') }}" />
                                </div>
                                <div class="mb-6">
                                    <label class="form-label" for="ecommerce-product-name">Thứ tự</label>
                                    <input type="text" class="form-control" placeholder="Thứ tự" name="order"
                                        aria-label="Thứ tự" value="{{ old('order') }}" />
                                </div>
                                <div class="mb-6  border-top mt-2">
                                    <label class="mb-1">Hình ảnh</label>
                                    <div class="dropzone needsclick p-0" id="dropzone-slider" data-type="avatar">
                                        <div class="dz-message needsclick">
                                            <p class="h4 needsclick pt-3 mb-2">Kéo thả ảnh vào đây</p>
                                            <p class="h6 text-muted d-block fw-normal mb-2">hoặc</p>
                                            <span class="note needsclick btn btn-sm btn-label-primary" id="btnBrowse">Tải
                                                ảnh lên</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>


                    <div class="col-12 order-3">
                        <div class="d-flex align-content-center flex-wrap gap-4">
                            @csrf
                            <button type="submit" id="save" class="btn btn-primary">Lưu</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
@endsection
@section('js')
    {!! JsValidator::formRequest('App\Http\Requests\Admin\SliderRequestAdmin') !!}

    <script src="{{ asset('assets/admin/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/libs/quill/quill.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/libs/select2/select2.js') }}"></script>

    <script src="{{ asset('assets/admin/libs/dropzone/dropzone-min.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/libs/jquery-repeater/jquery-repeater.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/libs/tagify/tagify.js') }}"></script>
    <script src="{{ asset('assets/admin/js/app-ecommerce-product-add.js') }}?v=1"></script>
    <script src="{{ asset('assets/admin/vendor/libs/shepherd/shepherd.js') }}"></script>
    <script>
        $(document).ready(function() {
            const previewTemplate = `<div class="dz-preview dz-file-preview">
                <div class="dz-details">
                <div class="dz-thumbnail">
                    <img data-dz-thumbnail>
                    <span class="dz-nopreview">Không có xem trước</span>
                    <div class="dz-success-mark"></div>
                    <div class="dz-error-mark"></div>
                    <div class="dz-error-message"><span data-dz-errormessage></span></div>
                    <div class="progress">
                    <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" data-dz-uploadprogress></div>
                    </div>
                </div>
                <div class="dz-filename" data-dz-name></div>
                <div class="dz-size" data-dz-size></div>
                </div>
            </div>`;

            function initializeDropzone(selector, multipleFiles = false) {
                const maxFilesize = 10;
                const maxFiles = multipleFiles ? null : 1;
                return new Dropzone(selector, {
                    previewTemplate: previewTemplate,
                    addRemoveLinks: true,
                    url: "{{ route('admin.file.uploadImage1') }}",
                    method: "post",
                    paramName: "file",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    maxFiles: maxFiles,
                    maxFilesize: maxFilesize,
                    acceptedFiles: ".jpeg,.jpg,.png,.gif,.webp",
                    dictDefaultMessage: multipleFiles ? "Chọn nhiều ảnh để tải lên" :
                        "Kéo hoặc chọn ảnh để tải lên",
                    chunking: true,
                    forceChunking: true,
                    chunkSize: 2 * 1024 * 1024,
                    parallelChunkUploads: true,
                    retryChunks: true,
                    retryChunksLimit: 5,
                    dictDefaultMessage: multipleFiles ? "Chọn nhiều ảnh để tải lên" :
                        "Kéo hoặc chọn ảnh để tải lên",
                    dictFallbackMessage: "Trình duyệt của bạn không hỗ trợ tính năng kéo thả tệp.",
                    dictInvalidFileType: "Loại tệp này không được chấp nhận.",
                    dictFileTooBig: `Tệp quá lớn. Kích thước tối đa cho phép: ${maxFilesize} MB.`,
                    dictResponseError: "Đã xảy ra lỗi khi tải tệp lên..",
                    dictCancelUpload: "Hủy tải lên",
                    dictCancelUploadConfirmation: "Bạn có chắc chắn muốn hủy tải lên tệp này?",
                    dictRemoveFile: "Xóa tệp",
                    dictMaxFilesExceeded: `Bạn chỉ có thể tải lên tối đa ${maxFiles} tệp.`,
                    dictUploadCanceled: "Tải lên đã bị hủy.",
                    init: function() {
                        var dropzone = this;
                        var fileType = dropzone.element.getAttribute('data-type');
                        this.on("success", function(file, response) {
                            file.upload.filename = response.filename;
                            file.upload.filepath = response.filepath;
                            console.log(file.upload);
                            file.upload.type = fileType;
                        });

                        this.on("error", function(file, response) {
                            console.error('Upload error:', response);
                            notifyMe('danger', response);
                        });

                        this.on("removedfile", function(file) {
                            var filename = file.upload.filename;
                            var filepath = file.upload.filepath;
                            var type = file.upload.type;
                            $.ajax({
                                type: 'get',
                                url: "{{ route('admin.file.deleteImage1') }}",
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                        'content')
                                },
                                data: {
                                    filename: filename,
                                    filepath: filepath,
                                    type: type,
                                    name: 'slider'
                                },
                                success: function(data) {
                                    console.log('JS xoa: ' + filename);
                                },
                                error: function(xhr, status, error) {
                                    console.error('Delete error:', xhr
                                        .responseText);
                                }
                            });
                        });

                        this.on("sending", function(file, xhr, formData) {
                            formData.append("type", fileType);
                        });
                    }
                });
            }

            var myDropzone = initializeDropzone("#dropzone-slider");

            const startBtn = document.querySelector('#question');
            const tourSteps = [{
                    title: 'Tiêu đề chính',
                    text: 'Đây là tiêu đề chính cho sản phẩm của bạn. Hãy nhập tiêu đề rõ ràng và dễ hiểu.',
                    element: '#title'
                },
                {
                    title: 'Giá',
                    text: 'Nhập giá của sản phẩm ở đây. Hãy chắc chắn rằng giá là chính xác.',
                    element: '[name="price"]'
                },
                {
                    title: 'Danh mục',
                    text: 'Chọn danh mục phù hợp cho sản phẩm của bạn.',
                    element: '#category'
                },
                {
                    title: 'Mô tả ngắn',
                    text: 'Cung cấp một mô tả ngắn gọn về sản phẩm của bạn.',
                    element: '[name="description"]'
                },
                {
                    title: 'Hiển thị',
                    text: 'Nếu bạn muốn sản phẩm hiển thị trên trang, hãy bật công tắc này.',
                    element: '.status'
                },
                {
                    title: 'Nội dung nút',
                    text: 'Nhập nội dung cho nút mà bạn muốn hiển thị.',
                    element: '[name="button_text"]'
                },
                {
                    title: 'Đường dẫn nút',
                    text: 'Nhập đường dẫn mà nút sẽ dẫn đến khi được nhấn.',
                    element: '[name="button_link_text"]'
                },
                {
                    title: 'Thứ tự',
                    text: 'Đặt thứ tự cho sản phẩm, nếu cần.',
                    element: '[name="order"]'
                },
                {
                    title: 'Hình ảnh',
                    text: 'Tải lên hình ảnh đại diện cho sản phẩm.',
                    element: '#dropzone-slider'
                },
                {
                    title: 'Lưu',
                    text: 'Sau khi hoàn tất thông tin, hãy nhấn lưu.',
                    element: '#save'
                }
            ];


            function setupTour(tour) {
                const backBtnClass = 'btn btn-sm btn-label-secondary md-btn-flat waves-effect waves-light',
                    nextBtnClass = 'btn btn-sm btn-primary btn-next waves-effect waves-light';

                tourSteps.forEach(step => {
                    tour.addStep({
                        title: step.title,
                        text: step.text,
                        attachTo: {
                            element: step.element,
                            on: 'top' // Đặt vị trí mặc định là 'top'
                        },
                        buttons: [{
                                text: 'Bỏ qua',
                                classes: backBtnClass,
                                action: tour.cancel
                            },
                            {
                                text: 'Quay lại',
                                classes: backBtnClass,
                                action: tour.back
                            },
                            {
                                text: 'Tiếp',
                                classes: nextBtnClass,
                                action: tour.next
                            }
                        ]
                    });
                });

                return tour;
            }

            if (startBtn) {
                startBtn.onclick = function() {
                    const tourVar = new Shepherd.Tour({
                        defaultStepOptions: {
                            scrollTo: false,
                            cancelIcon: {
                                enabled: true
                            }
                        },
                        useModalOverlay: true
                    });

                    setupTour(tourVar).start();
                };
            }


        });
    </script>
@endsection
