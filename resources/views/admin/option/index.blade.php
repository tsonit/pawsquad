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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
    <style>
        .accordion li {
            list-style: none !important;
        }
    </style>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="app-ecommerce">
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-1">Cài đặt</h4>
                    <a href="#" id="question" class="btn btn-link text-primary">
                        <i class="fas fa-question"></i>
                    </a>
                </div>

            </div>
            <div class="row product-adding">
                <div class="col-xl-12">
                    <div class="card tab2-card">
                        <form class="needs-validation user-add" action="{{ route('admin.options.postEditOption') }}"
                            method="POST">
                            <div class="card-body">
                                <ul class="nav nav-tabs tab-coupon" id="myTab" role="tablist">
                                    <li class="nav-item"><a class="nav-link active show" id="main-tab" data-bs-toggle="tab"
                                            href="#main" role="tab" aria-controls="main" aria-selected="true"
                                            data-original-title="" title="">Chính</a></li>
                                    <li class="nav-item"><a class="nav-link" id="tichhop-tabs" data-bs-toggle="tab"
                                            href="#tichhop" role="tab" aria-controls="tichhop" aria-selected="false"
                                            data-original-title="" title="">Tích hợp</a></li>
                                    <li class="nav-item"><a class="nav-link" id="mail-tabs" data-bs-toggle="tab"
                                            href="#mail" role="tab" aria-controls="mail" aria-selected="false"
                                            data-original-title="" title="">Mail</a></li>
                                    <li class="nav-item"><a class="nav-link" id="thongtin-tabs" data-bs-toggle="tab"
                                            href="#thongtin" role="tab" aria-controls="thongtin" aria-selected="false"
                                            data-original-title="" title="">Thông tin</a></li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade active show" id="main" role="tabpanel"
                                        aria-labelledby="main-tab">
                                        <h4>Chính</h4>
                                        <div class="form-group row">
                                            <label for="validationCustom1" class="col-xl-3 col-md-4">Trang chủ</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input class="form-control mb-2" type="text" name="APP_URL"
                                                    value="{{ old('APP_URL', env('APP_URL')) }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom1" class="col-xl-3 col-md-4">Tên hệ thống</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input class="form-control mb-2" type="text" name="APP_NAME"
                                                    value="{{ old('APP_NAME', env('APP_NAME')) }}">
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label for="validationCustom1" class="col-xl-3 col-md-4">Logo</label>
                                            <div class="col-xl-8 col-md-7">
                                                <div class="dropzone needsclick p-0" id="dropzone-logo"
                                                    data-type="avatar">
                                                    <div class="dz-message needsclick">
                                                        <p class="h4 needsclick pt-3 mb-2">Kéo thả ảnh vào đây</p>
                                                        <p class="h6 text-muted d-block fw-normal mb-2">hoặc</p>
                                                        <span class="note needsclick btn btn-sm btn-label-primary"
                                                            id="btnBrowse">Tải
                                                            ảnh lên</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="tichhop" role="tabpanel"
                                        aria-labelledby="tichhop-tabs">
                                        <div class="permission-block">
                                            <div class="attribute-blocks">
                                                <h5 class="f-w-600 mb-3">Tích hợp </h5>
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <label for="validationCustom1" class="col-xl-3 col-md-4">APPID
                                                            FACEBOOK</label>
                                                        <div class="col-xl-8 col-md-7">
                                                            <input class="form-control mb-2" type="text"
                                                                name="DB_IDAPP_FACEBOOK"
                                                                value="{{ old('DB_IDAPP_FACEBOOK', getOption('DB_IDAPP_FACEBOOK')) }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="validationCustom1" class="col-xl-3 col-md-4">GOOGLE
                                                            WEBMASTER</label>
                                                        <div class="col-xl-8 col-md-7">
                                                            <input class="form-control mb-2" type="text"
                                                                name="DB_KEY_VERIFY_GOOGLE_WEBMASTER"
                                                                value="{{ old('DB_KEY_VERIFY_GOOGLE_WEBMASTER', getOption('DB_KEY_VERIFY_GOOGLE_WEBMASTER')) }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="validationCustom1" class="col-xl-3 col-md-4">BING
                                                            WEBMASTER</label>
                                                        <div class="col-xl-8 col-md-7">
                                                            <input class="form-control mb-2" type="text"
                                                                name="DB_KEY_VERIFY_BING_WEBMASTER"
                                                                value="{{ old('DB_KEY_VERIFY_BING_WEBMASTER', getOption('DB_KEY_VERIFY_BING_WEBMASTER')) }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="validationCustom1" class="col-xl-3 col-md-4">Serect
                                                            Key
                                                            CaptchaV2</label>
                                                        <div class="col-xl-8 col-md-7">
                                                            <input class="form-control mb-2" type="text"
                                                                name="NOCAPTCHA_SECRET"
                                                                value="{{ old('NOCAPTCHA_SECRET', env('NOCAPTCHA_SECRET')) }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="validationCustom1" class="col-xl-3 col-md-4">Site Key
                                                            CaptchaV2</label>
                                                        <div class="col-xl-8 col-md-7">
                                                            <input class="form-control mb-2" type="text"
                                                                name="NOCAPTCHA_SITEKEY"
                                                                value="{{ old('NOCAPTCHA_SITEKEY', env('NOCAPTCHA_SITEKEY')) }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="mail" role="tabpanel"
                                        aria-labelledby="mail-tab">
                                        <h4>Mail</h4>
                                        <div class="form-group row">
                                            <label for="validationCustom1" class="col-xl-3 col-md-4">Kiểu</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input class="form-control mb-2" type="text" name="MAIL_MAILER"
                                                    value="{{ old('MAIL_MAILER', env('MAIL_MAILER')) }}">

                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom1" class="col-xl-3 col-md-4">Máy chủ</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input class="form-control mb-2" type="text" name="MAIL_HOST"
                                                    value="{{ old('MAIL_HOST', env('MAIL_HOST')) }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom1" class="col-xl-3 col-md-4">Port</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input class="form-control mb-2" type="text" name="MAIL_PORT"
                                                    value="{{ old('MAIL_PORT', env('MAIL_PORT')) }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom1" class="col-xl-3 col-md-4">Mã hóa</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input class="form-control mb-2" type="text" name="MAIL_ENCRYPTION"
                                                    value="{{ old('MAIL_ENCRYPTION', env('MAIL_ENCRYPTION')) }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom1" class="col-xl-3 col-md-4">Tài khoản</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input class="form-control mb-2" type="text" name="MAIL_USERNAME"
                                                    value="{{ old('MAIL_USERNAME', env('MAIL_USERNAME')) }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom1" class="col-xl-3 col-md-4">Mật khẩu</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input class="form-control mb-2" type="password" name="MAIL_PASSWORD"
                                                    value="{{ old('MAIL_PASSWORD', env('MAIL_PASSWORD')) }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom1" class="col-xl-3 col-md-4">Từ tài khoản</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input class="form-control mb-2" type="text" name="MAIL_FROM_ADDRESS"
                                                    value="{{ old('MAIL_FROM_ADDRESS', env('MAIL_FROM_ADDRESS')) }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom1" class="col-xl-3 col-md-4">Tên</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input class="form-control mb-2" type="text" name="MAIL_FROM_NAME"
                                                    value="{{ old('MAIL_FROM_NAME', env('MAIL_FROM_NAME')) }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="thongtin" role="tabpanel"
                                        aria-labelledby="thongtin-tabs">
                                        <div class="permission-block">
                                            <div class="attribute-blocks">
                                                <h5 class="f-w-600 mb-3">Thông tin </h5>
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <label for="validationCustom1" class="col-xl-3 col-md-4">Số điện
                                                            thoại</label>
                                                        <div class="col-xl-8 col-md-7">
                                                            <input class="form-control mb-2" type="text"
                                                                name="DB_PHONE"
                                                                value="{{ old('DB_PHONE', getOption('DB_PHONE')) }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="validationCustom1"
                                                            class="col-xl-3 col-md-4">Email</label>
                                                        <div class="col-xl-8 col-md-7">
                                                            <input class="form-control mb-2" type="text"
                                                                name="DB_EMAIL"
                                                                value="{{ old('DB_EMAIL', getOption('DB_EMAIL')) }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <h5 class="f-w-600 mb-3">Liên kết MXH </h5>
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <label for="validationCustom1"
                                                            class="col-xl-3 col-md-4">Facebook</label>
                                                        <div class="col-xl-8 col-md-7">
                                                            <input class="form-control mb-2" type="text"
                                                                name="DB_SOCIAL_LINK_FACEBOOK"
                                                                value="{{ old('DB_SOCIAL_LINK_FACEBOOK', getOption('DB_SOCIAL_LINK_FACEBOOK')) }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="validationCustom1"
                                                            class="col-xl-3 col-md-4">Youtube</label>
                                                        <div class="col-xl-8 col-md-7">
                                                            <input class="form-control mb-2" type="text"
                                                                name="DB_SOCIAL_LINK_YOUTUBE"
                                                                value="{{ old('DB_SOCIAL_LINK_YOUTUBE', getOption('DB_SOCIAL_LINK_YOUTUBE')) }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="validationCustom1"
                                                            class="col-xl-3 col-md-4">Tiktok</label>
                                                        <div class="col-xl-8 col-md-7">
                                                            <input class="form-control mb-2" type="text"
                                                                name="DB_SOCIAL_LINK_TIKTOK"
                                                                value="{{ old('DB_SOCIAL_LINK_TIKTOK', getOption('DB_SOCIAL_LINK_TIKTOK')) }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="validationCustom1"
                                                            class="col-xl-3 col-md-4">Zalo</label>
                                                        <div class="col-xl-8 col-md-7">
                                                            <input class="form-control mb-2" type="text"
                                                                name="DB_SOCIAL_LINK_ZALO"
                                                                value="{{ old('DB_SOCIAL_LINK_ZALO', getOption('DB_SOCIAL_LINK_ZALO')) }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="pull-right">
                                    @csrf
                                    <button type="submit" class="btn btn-primary mt-3">Lưu cài đặt</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('assets/admin/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/libs/quill/quill.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/libs/select2/select2.js') }}"></script>

    <script src="{{ asset('assets/admin/libs/dropzone/dropzone-min.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/libs/jquery-repeater/jquery-repeater.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/libs/tagify/tagify.js') }}"></script>
    <script src="{{ asset('assets/admin/js/app-ecommerce-product-add.js') }}?v=1"></script>
    <script src="{{ asset('assets/admin/vendor/libs/shepherd/shepherd.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script src="{{ asset('assets/admin/vendor/libs/sortablejs/sortable.js') }}"></script>
    <script src="{{ asset('assets/admin/js/extended-ui-drag-and-drop.js') }}"></script>

    <script>
        $(document).ready(function() {
            const previewTemplate = `<div class="dz-preview dz-file-preview">
                <div class="dz-details">
                <div class="dz-thumbnail">
                    <a href="#" data-fancybox="gallery" data-dz-thumbnail><img data-dz-thumbnail></a>
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
            Fancybox.bind("[data-fancybox]", {
                infinite: false,
                transitionEffect: "fade",
                buttons: ["zoom", "close"],
                loop: false,
                toolbar: true,
                thumbs: {
                    autoStart: true
                },
                zoom: true,
            });

            function initializeDropzone(selector, multipleFiles = false) {
                const maxFilesize = 10;
                const maxFiles = multipleFiles ? null : 1;
                let hasAvatarImage = null;
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
                        this.on("addedfile", function(file) {
                            if (dropzone.files.length >= 1 && !file.uploaded && fileType ==
                                "avatar") {
                                if (hasAvatarImage) {
                                    if (file.previewElement) {
                                        file.previewElement.remove();
                                    }
                                    notifyMe('danger',
                                        "Đã có ảnh avatar. Bạn không thể tải lên thêm ảnh.");
                                } else {
                                    hasAvatarImage = true;
                                }
                            }
                        });
                        $.ajax({
                            type: 'GET',
                            url: "{{ route('admin.file.getOldImage1') }}",
                            data: {
                                name: 'logo'
                            },
                            success: function(response) {
                                console.log(response)
                                if (response.status === 'success') {
                                    var avatarImage = "{{ session('logo.image') }}";
                                    if (avatarImage && fileType === 'avatar') {
                                        var mockFile = {
                                            name: response.filename,
                                            size: response.size,
                                            filepath: response.filepath,
                                            type: "avatar"
                                        };
                                        hasAvatarImage = true;

                                        dropzone.emit("addedfile", mockFile);
                                        dropzone.emit("thumbnail", mockFile,
                                            response.url);
                                        dropzone.emit("complete", mockFile);
                                        var thumbnailElement = mockFile
                                            .previewElement.querySelector(
                                                '[data-fancybox]');
                                        if (thumbnailElement) {
                                            thumbnailElement.href = response
                                                .url; // Cập nhật URL của hình ảnh
                                        }
                                    }
                                }
                            },

                            error: function(xhr, status, error) {
                                console.log(xhr.responseText);
                            }
                        });

                        this.on("success", function(file, response) {
                            file.upload.filename = response.filename;
                            file.upload.filepath = response.filepath;
                            file.upload.type = fileType;
                            if (fileType == 'avatar') {
                                hasAvatarImage = true;
                            }
                            var thumbnailElement = file.previewElement.querySelector(
                                '[data-fancybox]');
                            if (thumbnailElement) {
                                thumbnailElement.href = response
                                    .url; // Cập nhật URL của hình ảnh
                            }
                        });

                        this.on("error", function(file, response) {
                            console.error('Upload error:', response);
                            notifyMe('danger', response);
                        });

                        this.on("removedfile", function(file) {
                            var filename = file.name;
                            if (file.upload) {
                                var filepath = file.upload.filepath;
                            } else {
                                var filepath = file.filepath;
                            }

                            var type = file.type;
                            $(selector).addClass(
                                "dropzone needsclick p-0 dz-clickable dz-started");
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
                                    name: 'logo',
                                },
                                success: function(data) {
                                    if (fileType == 'avatar') {
                                        hasAvatarImage = false;
                                    }
                                    var dropzoneElement = dropzone
                                        .element;
                                    var remainingFiles = dropzoneElement
                                        .childElementCount;

                                    var isSingleFile = remainingFiles <= 1;

                                    if (isSingleFile) {
                                        $(selector).removeClass("dz-started")
                                            .addClass(
                                                "dropzone needsclick p-0 dz-clickable"
                                            );
                                    } else {
                                        $(selector).addClass("dz-started");
                                    }
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

            var myDropzone = initializeDropzone("#dropzone-logo");

            window.Helpers.swipeIn('.drag-target', function(e) {
                window.Helpers.setCollapsed(false);
            });


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
