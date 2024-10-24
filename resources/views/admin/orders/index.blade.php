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
    <style>
        .flatpickr-calendar {
            left: 50% !important;
            top:50%!important;
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
                <h5 class="card-title">Danh sách hoá đơn</h5>
                <small class="text-muted">Đây là hoá đơn</small>

            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-products table">
                    <thead class="border-top">
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>Thông tin</th>
                            <th>Trạng thái</th>
                            <th>Phương thức</th>
                            <th>Thành tiền</th>
                            <th>Ngày thanh toán</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

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
    <script>
        $(document).ready(function() {
            console.log($.fn.dataTable.version);

            $('.datatables-products').DataTable({
                processing: true,
                serverSide: true,
                language: {
                    url: "{{ asset('assets/admin/vendor/libs/datatables-bs5/vi.json') }}",
                },
                ajax: {
                    url: "{{ route('admin.orders.index') }}",
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
                        data: 'shipment_status'
                    },
                    {
                        data: 'order_status'
                    },
                    {
                        data: 'payment_method'
                    },
                    {
                        data: 'total_amount'
                    },
                    {
                        data: 'order_date'
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
                        // Info
                        targets: 2,
                        render: function(data, type, full, meta) {
                            return `
                                <div class="d-flex flex-column">
                                    <span class="mb-1">Tên: <b>${limitText(full['name'], 20)}</b></span>
                                    <span class="mb-1">SĐT: <b>${full['phone']}</b></span>
                                    ${full['discount'] !== '0%' ? `<span>Giảm giá: <b>${full['discount']}</b></span>` : ''}
                                </div>
                            `;
                        }
                    },
                    {
                        // Order status
                        targets: 3,
                        render: function(data, type, full, meta) {
                            var statusMap = {
                                'PAID': '<span class="badge bg-success">Đã thanh toán</span>',
                                'CANCELED': '<span class="badge bg-danger">Đã huỷ</span>',
                                'PENDING': '<span class="badge bg-warning">Chưa thanh toán</span>',
                            };
                            return statusMap[full['order_status']] ||
                                '<span class="badge bg-secondary">N/A</span>';
                        }
                    },
                    {
                        // Payment method
                        targets: 4,
                        render: function(data, type, full, meta) {
                            var paymentMap = {
                                'VNPAY': '<span class="badge bg-primary">VNPAY</span>',
                                'COD': '<span class="badge bg-info">COD</span>',
                            };

                            return paymentMap[full['payment_method']] ||
                                '<span class="badge bg-secondary">N/A</span>';
                        }
                    },
                    {
                        // Total amount
                        targets: 5,
                        render: function(data, type, full, meta) {
                            // Định dạng số thành tiền Việt Nam
                            var formattedAmount = new Intl.NumberFormat('vi-VN', {
                                style: 'currency',
                                currency: 'VND'
                            }).format(full['total_amount']);
                            return formattedAmount.replace('₫', '') + 'đ';
                        }
                    },
                    {
                        // Order date
                        targets: 6,
                        render: function(data, type, full, meta) {
                            return full['order_date'];
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
                    text: '<i class="ti ti-calendar ti-xs me-md-2" id="filterDay"></i>',
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
                }],
                // For responsive popup
                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.modal({
                            header: function(row) {
                                var data = row.data();
                                return 'Chi tiết hoá đơn - ' + data['id'];
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
            $('.datatables-products').append('<input type="hidden" id="dateRange" style="display:none">');
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
