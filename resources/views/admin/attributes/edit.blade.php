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
                    <h4 class="mb-1">Sửa thuộc tính - {{ $data->name }}</h4>
                    <a href="#" id="question" class="btn btn-link text-primary">
                        <i class="fas fa-question"></i>
                    </a>
                </div>

            </div>
            <form id="form-validate" method="POST">
                <div class="row ">
                    <div class="col-12 col-lg-12 order-1">
                        <div class="card mb-6 ">
                            <div class="card-header">
                                <h5 class="card-tile mb-0">Thông tin</h5>

                            </div>
                            <div class="card-body">
                                <div class="mb-6">
                                    <label class="form-label mb-1" for="attributeset"> Nhóm thuộc tính </label>
                                    <select id="attributeset" class="select2 form-select"
                                        data-placeholder="Chọn nhóm thuộc tính" name="attributeset">
                                        <option value="">Chọn nhóm thuộc tính</option>
                                        @forelse($attributeSet as $row)
                                            <option value="{{ $row->id }}"
                                                {{ old('attributeset', $data->attribute_set_id) == $row->id ? 'selected' : '' }}>
                                                {{ $row->name }}
                                            </option>
                                        @empty
                                            <option value="" disabled>Không có nhóm thuộc tính</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="mb-6">
                                    <label class="form-label" for="ecommerce-product-name">Tên thuộc tính</label>
                                    <input type="text" class="form-control" id="ecommerce-product-name"
                                        placeholder="Tên thuộc tính" name="name" aria-label="Tên thuộc tính"
                                        value="{{ old('name', $data->name) }}" />
                                </div>
                                <div class="mb-6">
                                    <label class="form-label mb-1" for="categories"> Chọn danh mục game
                                    </label>
                                    <select id="categories" class="categories form-select"
                                        data-placeholder="Chọn danh mục game" name="categories" multiple="multiple">
                                        <option value="">Chọn danh mục game</option>
                                        @forelse($categories as $row)
                                            @if ($row->parent_id == null)
                                                <!-- Hiển thị danh mục cha -->
                                                <option value="{{ $row->id }}"
                                                    {{ old('categories', $data->categories) == $row->id ? 'selected' : '' }}>
                                                    {{ $row->name }}
                                                </option>

                                                <!-- Hiển thị danh mục con -->
                                                @foreach ($categories->where('parent_id', $row->id) as $child)
                                                    <option value="{{ $child->id }}"
                                                        data-parent-id="{{ $child->parent_id }}"
                                                        {{ old('categories') == $child->id ? 'selected' : '' }}>
                                                        -- {{ $child->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        @empty
                                            <option value="" disabled>Không có danh mục game</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="align-items-center border-top pt-2 status">
                                    <label class="form-label mb-1 me-3" for="status"> Hiển thị
                                    </label>
                                    <label class="switch switch-square">
                                        <input type="checkbox" name="status" class="switch-input"
                                            {{ old('status', $data->status) ? 'checked' : '' }}>
                                        <span class="switch-toggle-slider">
                                            <span class="switch-on"><i class="ti ti-check"></i></span>
                                            <span class="switch-off"><i class="ti ti-x"></i></span>
                                        </span>
                                    </label>
                                </div>
                                <div class="mb-6">
                                    <label class="form-label mb-1" for="value"> Giá trị thuộc tính <a
                                            href="javascript:void(0)" class="btn btn-default fw-bold" id="add-new-value">
                                            <i class="fa fa-plus me-2"></i> Thêm
                                        </a></label>
                                    <div id="attribute-values-wrapper">
                                        <div class="table-responsive">
                                            <table class="options table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th style="width:65px"></th>
                                                        <th>Giá trị</th>
                                                        <th style="width:10px"></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="attribute-values">

                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 order-2">
                        <div class="d-flex align-content-center flex-wrap gap-4">
                            @csrf
                            @method("PUT")
                            <button type="submit" id="save" class="btn btn-primary">Lưu</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
@endsection
@section('js')
    {!! JsValidator::formRequest('App\Http\Requests\Admin\AttributesRequestAdmin') !!}

    <script src="{{ asset('assets/admin/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/libs/quill/quill.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/libs/select2/select2.js') }}"></script>

    <script src="{{ asset('assets/admin/libs/dropzone/dropzone-min.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/libs/jquery-repeater/jquery-repeater.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/libs/tagify/tagify.js') }}"></script>
    <script src="{{ asset('assets/admin/js/app-ecommerce-product-add.js') }}?v=1"></script>
    <script src="{{ asset('assets/admin/vendor/libs/shepherd/shepherd.js') }}"></script>
    <!-- Vendors JS -->
    <script src="{{ asset('assets/admin/vendor/libs/sortablejs/sortable.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('assets/admin/js/extended-ui-drag-and-drop.js') }}"></script>

    <script>
        $(document).ready(function() {


            var select2 = $('.categories');

            if (select2.length) {
                select2.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        dropdownParent: $this.parent(),
                        placeholder: $this.data('placeholder'),
                        tags: true, // Kích hoạt chế độ tag
                        closeOnSelect: false // Không đóng khi chọn danh mục cha
                    });

                    // Bắt sự kiện thay đổi khi chọn danh mục
                    $this.on('select2:select', function(e) {
                        var selectedId = e.params.data.id;

                        // Tìm các option con thuộc về danh mục cha được chọn
                        var childOptions = $(this).find('option').filter(function() {
                            return $(this).data('parent-id') == selectedId;
                        });

                        // Thêm tất cả các danh mục con vào danh sách đã chọn nếu chưa có
                        var selectedValues = $this.val() || [];
                        childOptions.each(function() {
                            var optionValue = $(this).val();
                            if (!selectedValues.includes(optionValue)) {
                                selectedValues.push(optionValue);
                            }
                        });

                        // Cập nhật select2 với các giá trị đã chọn
                        $this.val(selectedValues).trigger('change');
                    });
                });
            }

            let valuesCount = 0;
            const attributes = @json(json_decode($data->value, true));
            // Hàm thêm giá trị thuộc tính vào bảng
            function addAttributeValue(value = {
                id: "",
                value: ""
            }) {
                const template = `
        <tr>
            <td class="text-center">
                <span class="drag-handle">
                    <i class="fa"></i>
                    <i class="fa"></i>
                </span>
            </td>
            <td>
                <input type="hidden" name="values[${valuesCount}][id]" value="${value.id}">
                <div class="form-group">
                    <input type="text" name="values[${valuesCount}][value]" value="${value.value}" class="form-control">
                </div>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-default delete-row">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        </tr>
    `;

                valuesCount++;
                document.querySelector("#attribute-values").insertAdjacentHTML("beforeend", template);
            }

            // Thêm giá trị từ dữ liệu JSON
            function populateValues() {
                attributes.forEach((item, index) => {
                    addAttributeValue({
                        id: item.id,
                        value: item.value
                    });
                });
            }

            // Xử lý sự kiện
            function eventListeners() {
                document.querySelector("#add-new-value").addEventListener("click", () => {
                    addAttributeValue();
                });

                document.querySelector("#attribute-values").addEventListener("click", (event) => {
                    if (event.target.closest(".delete-row")) {
                        event.target.closest("tr").remove();
                    }
                });
            }

            // Kích hoạt Sortable cho danh sách giá trị
            function sortable() {
                Sortable.create(document.getElementById("attribute-values"), {
                    handle: ".drag-handle",
                    animation: 150
                });
            }

            // Khởi tạo
            if (document.querySelector("#attribute-values-wrapper")) {
                populateValues(); // Thêm giá trị từ dữ liệu JSON
                eventListeners();
                sortable();
                window.admin.removeSubmitButtonOffsetOn("#values");
            }

            const startBtn = document.querySelector('#question');
            const tourSteps = [{
                    title: 'Tên thuộc tính',
                    text: 'Đây là thuộc tính cha. Dạng tướng,trang phục,ruby,...',
                    element: '[name="name"]'
                },
                {
                    title: 'Lưu',
                    text: 'Sau khi hoàn tất những thông tin thì lưu lại.',
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