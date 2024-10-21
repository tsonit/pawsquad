@extends('layouts.admin')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/quill/editor.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/dropzone/dropzone.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/tagify/tagify.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/shepherd/shepherd.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('assets/admin/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/jquery-timepicker/jquery-timepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/pickr/pickr-themes.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/tagify/tagify.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/shepherd/shepherd.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('assets/admin/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/jquery-timepicker/jquery-timepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/pickr/pickr-themes.css') }}" />
    <style>
        .flatpickr-calendar {
            left: 50% !important;
            transform: translateX(-50%) !important;
        }

        @media (max-width: 768px) {
            #dateRange {
                display: none;
            }
        }
    </style>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">


        <!-- Category List Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Danh sách đặt lịch</h5>
                <small class="text-muted">Đây là đặt lịch</small>

            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-products table">
                    <thead class="border-top">
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>Thông tin</th>
                            <th>Dịch vụ</th>
                            <th>Trạng thái</th>
                            <th>Ngày đặt lịch</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <!-- Quickview -->
        <div class="modal fade" id="quickview_modal">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content min-h-400 h-100">
                    <div class="modal-header bg-white">
                        <h5>Sửa đặt lịch </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body bg-white h-100">
                        <div class="data-preloader-wrapper d-flex align-items-center justify-content-center min-h-400">
                            <div class="" role="status">
                                <span class="sr-only"></span>
                            </div>
                        </div>

                        <div class="booking-info">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Quickview -->
    </div>
@endsection
@section('js')
    <script src="{{ asset('assets/admin/vendor/libs/select2/select2.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script src="{{ asset('assets/admin/vendor/libs/block-ui/block-ui.js') }}"></script>

    <script src="{{ asset('assets/admin/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/libs/jquery-timepicker/jquery-timepicker.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/libs/pickr/pickr.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/libs/flatpickr/vn.js') }}?v=1"></script>


    <script src="{{ asset('assets/admin/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/libs/jquery-timepicker/jquery-timepicker.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/libs/pickr/pickr.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/libs/flatpickr/vn.js') }}?v=1"></script>
    <script>
        "use strict"
        // tooltip
        $(function() {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });

        function showBookingModal(bookingId) {
            $('.modal').modal('hide');
            $('#quickview_modal .product-info').html(null);
            $('.data-preloader-wrapper>div').addClass('spinner-border');
            $('.data-preloader-wrapper').addClass('min-h-400');
            $('#quickview_modal').modal('show');

            $.post('{{ route('admin.booking.showInfoBooking') }}', {
                _token: '{{ csrf_token() }}',
                id: bookingId
            }, function(data) {
                setTimeout(() => {
                    $('.data-preloader-wrapper>div').removeClass('spinner-border');
                    $('.data-preloader-wrapper').removeClass('min-h-400');
                    $('#quickview_modal .booking-info').html(data);
                    $('.select2').select2({
                        dropdownParent: $('#quickview_modal'),
                        language: 'vi',
                        width: '100%'
                    });
                    const scheduled_at = document.querySelector('#scheduled_at');
                    if (scheduled_at) {
                        const startDatePicker = scheduled_at.flatpickr({
                            enableTime: true,
                            "locale": "vn",
                            dateFormat: 'd/m/Y H:i',
                            onChange: function(selectedDates, dateStr, instance) {
                                const endDateObj = end_date._flatpickr.selectedDates[0];
                                end_date._flatpickr.set('minDate', selectedDates[
                                    0]);
                            }
                        });
                    }
                }, 200);
            });

        }

        $('#quickview_modal').on('hide.bs.modal', function(e) {
            $('#quickview_modal .booking-info').html(null);
        });
    </script>
    <script>
        $(document).ready(function() {
            function getStatusBooking(type) {
                switch (type) {
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

            $('.datatables-products').DataTable({
                processing: true,
                serverSide: true,
                language: {
                    url: "{{ asset('assets/admin/vendor/libs/datatables-bs5/vi.json') }}",
                },
                ajax: {
                    url: "{{ route('admin.booking.index') }}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function(d) {
                        d.date_range = $('#dateRange').val(); // Lấy giá trị range ngày
                    },
                    dataSrc: function(json) {
                        window.status_flag = json.status_flag || [];
                        return json.data;
                    },
                    "beforeSend": function() {
                        $('.datatables-products').block({
                            message: '<div class="spinner-border text-primary" role="status"></div>',
                            css: {
                                backgroundColor: 'transparent',
                                border: '0'
                            },
                            overlayCSS: {
                                backgroundColor: '#fff',
                                opacity: 0.8
                            }
                        });
                    }
                },
                drawCallback: function(settings) {
                    // Tắt spinner sau khi bảng đã được vẽ
                    $('.datatables-products').unblock();
                },
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'id'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'service_id'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'scheduled_at'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                columnDefs: [{
                        // For Responsive
                        className: 'control',
                        responsivePriority: 2,
                        searchable: false,
                        targets: 0,
                        render: function(data, type, full, meta) {
                            return '';
                        }
                    },
                    {
                        // ID
                        targets: 1,
                        render: function(data, type, full, meta) {
                            var $id = full['id'];
                            var $row_output = '<a href="">#' + $id + '</a>';
                            return $row_output;
                        }
                    },
                    {
                        // Name
                        targets: 2,
                        render: function(data, type, full, meta) {
                            return `
                                <div class="d-flex flex-column">
                                    <span class="mb-1">${limitText(full['name'], 20)}</span>
                                    <span class="mb-1">${full['email']}</span>
                                    <span>${full['phone']}</span>
                                </div>
                            `;
                        }
                    },
                    {
                        // Service
                        targets: 3,
                        render: function(data, type, full, meta) {
                            var $service = full['service'];
                            return $service;
                        }
                    },
                    {
                        // Status
                        targets: 4,
                        render: function(data, type, full, meta) {
                            var statusValue = full['status']; // Lấy giá trị status
                            var statusText = getStatusBooking(statusValue);
                            var statusBadge = {
                                'Đã tạo': '<span class="me-2 badge d-flex align-items-center justify-content-center bg-primary" style="white-space: nowrap;">Đã tạo</span>',
                                'Đang xử lý': '<span class="me-2 badge d-flex align-items-center justify-content-center bg-warning" style="white-space: nowrap;">Đang xử lý</span>',
                                'Hoàn thành': '<span class="me-2 badge d-flex align-items-center justify-content-center bg-success" style="white-space: nowrap;">Hoàn thành</span>',
                                'Đã hủy': '<span class="me-2 badge d-flex align-items-center justify-content-center bg-danger" style="white-space: nowrap;">Đã hủy</span>',
                                'Tạm hoãn': '<span class="me-2 badge d-flex align-items-center justify-content-center bg-secondary" style="white-space: nowrap;">Tạm hoãn</span>',
                                'Đã xác nhận': '<span class="me-2 badge d-flex align-items-center justify-content-center bg-info" style="white-space: nowrap;">Đã xác nhận</span>',
                                'Không thành công': '<span class="me-2 badge d-flex align-items-center justify-content-center bg-dark" style="white-space: nowrap;">Không thành công</span>',
                                'N/A': '<span class="me-2 badge d-flex align-items-center justify-content-center bg-light text-dark" style="white-space: nowrap;">N/A</span>'
                            };

                            return (
                                "<div class='d-inline-flex align-items-center'>" +
                                statusBadge[statusText] + // Hiển thị badge tương ứng
                                "</div>"
                            );
                        }
                    },
                    {
                        // scheduled
                        targets: 5,
                        render: function(data, type, full, meta) {
                            var scheduled = full['scheduled'];
                            return scheduled;
                        }
                    },
                ],
                order: [
                    [1, 'desc']
                ],
                dom: '<"row"' +
                    '<"col-12 col-md-6 d-flex align-items-center justify-content-center justify-content-md-start gap-2"l<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start"B>>' +
                    '<"col-12 col-md-6 d-flex align-items-center justify-content-end flex-column flex-md-row pe-5 gap-md-4 mt-n5 mt-md-0"f<"invoice_status mb-6 mb-md-0">>' +
                    '>t' +
                    '<"row"' +
                    '<"col-sm-12 col-md-6"i>' +
                    '<"col-sm-12 col-md-6"p>' +
                    '>',
                displayLength: 10,
                lengthMenu: [10, 20, 50, 75, 100],
                // Buttons with Dropdown
                buttons: [{
                        text: '<i class="ti ti-trash ti-xs me-md-2"></i><span class="d-md-inline-block d-none"></span>',
                        className: 'ms-2 btn btn-warning waves-effect waves-light',
                        action: function(e, dt, button, config) {
                            window.location =
                                "{{ route('admin.booking.trashed') }}";
                        }
                    },
                    {
                        text: '<i class="ti ti-calendar ti-xs me-md-2"></i>',
                        className: 'ms-2 btn btn-primary',
                        action: function(e, dt, button, config) {
                            flatpickr("#dateRange", {
                                mode: "range",
                                enableTime: false,
                                "locale": "vn",
                                dateFormat: "Y-m-d",
                                onClose: function(selectedDates, dateStr, instance) {
                                    dt.ajax
                                        .reload();
                                },
                            }).open();
                        }
                    }
                ],
                // For responsive popup
                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.modal({
                            header: function(row) {
                                var data = row.data();
                                return 'Chi tiết đặt lịch - ' + data['id'];
                            }
                        }),
                        type: 'column',
                        renderer: function(api, rowIdx, columns) {
                            var data = $.map(columns, function(col, i) {
                                return col.title !==
                                    '' // ? Do not show row in modal popup if title is blank (for check box)
                                    ?
                                    '<tr data-dt-row="' +
                                    col.rowIndex +
                                    '" data-dt-column="' +
                                    col.columnIndex +
                                    '">' +
                                    '<td>' +
                                    col.title +
                                    ':' +
                                    '</td> ' +
                                    '<td>' +
                                    col.data +
                                    '</td>' +
                                    '</tr>' :
                                    '';
                            }).join('');

                            return data ? $('<table class="table"/><tbody />').append(data) : false;
                        }
                    }
                }
            });
            $('body').append('<input type="hidden" id="dateRange">');
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
        });
    </script>
@endsection
