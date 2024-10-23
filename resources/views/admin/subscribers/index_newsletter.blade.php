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
                    <h4 class="mb-1">Gửi chiến dịch</h4>
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
                                    <label class="form-label" for="ecommerce-product-name">Gửi đến</label>
                                    <select id="choices-email" class="select2 form-select" data-placeholder="Chọn người gửi"
                                        name="to[]">
                                        @forelse($subscribers as $row)
                                            <option value="{{ $row->email }}"
                                                {{ in_array($row->email, old('to', [])) ? 'selected' : '' }}>
                                                {{ $row->fullname }} - {{ $row->email }}
                                            </option>
                                        @empty
                                            <option value="" disabled>Không có người gửi</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="mb-6">
                                    <label class="form-label" for="ecommerce-product-name">Tiêu đề mail</label>
                                    <input type="text" class="form-control" placeholder="Tiêu đề mail" name="subject"
                                        aria-label="Tiêu đề mail" value="{{ old('subject') }}" />
                                </div>
                                <div class="mb-6">
                                    <label class="form-label" for="ecommerce-product-name">Chiến dịch</label>
                                    <select class="select2 form-select" id="campaign" data-placeholder="Chọn chiến dịch"
                                        name="campaign">
                                        @forelse($themes as $row)
                                            <option value="{{ $row->email_type }}"
                                                {{ old('campaign') === $row->email_type ? 'selected' : '' }}>
                                                {{ $row->email_type }}
                                            </option>
                                        @empty
                                            <option value="" disabled>Không có chiến dịch</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 order-3">
                        <div class="d-flex align-content-center flex-wrap gap-4">
                            @csrf
                            <button type="submit" id="save" class="btn btn-primary">Gửi</button>
                        </div>
                    </div>
                </div>
            </form>

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
    <script>
        $(document).ready(function() {

            var selectTo = $('#choices-email');

            selectTo.select2({
                tags: true,
                multiple: true,
                tokenSeparators: [','],
                minimumResultsForSearch: 1,
                templateResult: function(data) {
                    if (data.id === 'select-all') {
                        return $('<span class="select-all-option">Chọn tất cả</span>');
                    }
                    return data.text;
                },
                templateSelection: function(selected) {
                    if (selected.id === 'select-all') {
                        return 'Hủy chọn tất cả';
                    }
                    return selected.text;
                },
                createTag: function(params) {
                    return null;
                }
            });

            // Add "Chọn tất cả" option
            selectTo.append($('<option>', {
                value: 'select-all',
                text: 'Chọn tất cả'
            }));

            selectTo.on('select2:select', function(e) {
                if (e.params.data.id === 'select-all') {
                    $(this).find('option').not(':selected').prop('selected', true);
                    $(this).find('option[value="select-all"]').text('Hủy chọn tất cả');
                    $(this).trigger('change');
                }
            });

            selectTo.on('select2:unselect', function(e) {
                if (e.params.data.id === 'select-all') {
                    $(this).find('option:selected').prop('selected', false);
                    $(this).find('option[value="select-all"]').text('Chọn tất cả');
                    $(this).trigger('change');
                }
            });

            selectTo.on('select2:selecting', function(e) {
                var selectedValue = e.params.args.data.id;
                if (!isValueInApiList(selectedValue)) {
                    e.preventDefault();
                }
            });

            function isValueInApiList(value) {
                var validEmails = [];
                selectTo.find('option').each(function() {
                    validEmails.push($(this).val());
                });

                if (value === 'select-all') {
                    return true;
                }
                return validEmails.includes(value);
            }

            const startBtn = document.querySelector('#question');
            const tourSteps = [{
                    title: 'Gửi đến',
                    text: 'Chọn người nhận email từ danh sách.',
                    element: '#choices-email'
                },
                {
                    title: 'Tiêu đề mail',
                    text: 'Nhập tiêu đề cho email của bạn.',
                    element: '[name="subject"]'
                },
                {
                    title: 'Chiến dịch',
                    text: 'Chọn chiến dịch mà bạn muốn gửi email.',
                    element: '[name="campaign"]'
                },
                {
                    title: 'Gửi',
                    text: 'Sau khi hoàn tất thông tin, nhấn vào nút "Gửi" để gửi chiến dịch này.',
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
