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
                    <h4 class="mb-1">Sửa menu</h4>
                    <a href="#" id="question" class="btn btn-link text-primary">
                        <i class="fas fa-question"></i>
                    </a>
                </div>

            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Thêm Vào Menu</h5>
                        </div>
                        <div class="card-body">
                            <div class="accordion" id="menuAccordion">
                                <!-- Trang Section -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingPages">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapsePages" aria-expanded="true"
                                            aria-controls="collapsePages">
                                            Trang
                                        </button>
                                    </h2>
                                    <div id="collapsePages" class="accordion-collapse collapse show"
                                        aria-labelledby="headingPages">
                                        <div class="accordion-body my-3">
                                            <ul class="m-0 p-0">
                                                <li>
                                                    <input type="checkbox" id="page-1" class="mx-1"
                                                        data-url="{{ route('home') }}" data-name="Trang chủ">
                                                    <label for="page-1">Trang chủ</label>
                                                </li>
                                                <li>
                                                    <input type="checkbox" id="page-2" class="mx-1"
                                                        data-url="{{ route('about') }}" data-name="Giới thiệu">
                                                    <label for="page-2">Giới thiệu</label>
                                                </li>
                                                <li>
                                                    <input type="checkbox" id="page-3" class="mx-1"
                                                        data-url="{{ route('product') }}" data-name="Sản phẩm">
                                                    <label for="page-3">Sản phẩm</label>
                                                </li>
                                                <li>
                                                    <input type="checkbox" id="page-4" class="mx-1"
                                                        data-url="{{ route('brand') }}" data-name="Thương hiệu">
                                                    <label for="page-3">Thương hiệu</label>
                                                </li>
                                                <li>
                                                    <input type="checkbox" id="page-5" class="mx-1"
                                                        data-url="{{ route('blog') }}" data-name="Tin tức">
                                                    <label for="page-3">Tin tức</label>
                                                </li>
                                            </ul>
                                            <button class="btn btn-primary mt-3" id="addPagesToMenu">Thêm vào menu</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Liên kết tùy chỉnh Section -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingCustomLinks">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseCustomLinks" aria-expanded="false"
                                            aria-controls="collapseCustomLinks">
                                            Liên kết tuỳ chỉnh
                                        </button>
                                    </h2>
                                    <div id="collapseCustomLinks" class="accordion-collapse collapse"
                                        aria-labelledby="headingCustomLinks">
                                        <div class="accordion-body my-3">
                                            <div class="mb-3">
                                                <label for="custom-link-url" class="form-label">Đường dẫn</label>
                                                <input type="url" class="form-control" id="custom-link-url"
                                                    placeholder="https://example.com">
                                            </div>
                                            <div class="mb-3">
                                                <label for="custom-link-text" class="form-label">Văn bản</label>
                                                <input type="text" class="form-control" id="custom-link-text"
                                                    placeholder="Văn bản liên kết">
                                            </div>
                                            <button class="btn btn-primary" id="addCustomLinkToMenu">Thêm vào
                                                menu</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Danh mục Section -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingCategories">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseCategories"
                                            aria-expanded="false" aria-controls="collapseCategories">
                                            Danh mục
                                        </button>
                                    </h2>
                                    <div id="collapseCategories" class="accordion-collapse collapse"
                                        aria-labelledby="headingCategories">
                                        <div class="accordion-body my-3">
                                            <ul class="m-0 p-0">
                                                @if ($categories && $categories->isNotEmpty())
                                                    @foreach ($categories as $category)
                                                        <li>
                                                            <input type="checkbox" id="category-{{ $category->id }}"
                                                                class="mx-1"
                                                                data-url="{{ route('list.category', ['slug' => $category->slug]) }}"
                                                                data-name="{{ $category->name }}">
                                                            <label
                                                                for="category-{{ $category->id }}">{{ $category->name }}</label>
                                                        </li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                            <button class="btn btn-primary mt-3" id="addCategoriesToMenu">Thêm vào
                                                menu</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Trang tùy chỉnh Section -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingCustomPages">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseCustomPages"
                                            aria-expanded="false" aria-controls="collapseCustomPages">
                                            Trang tuỳ chỉnh
                                        </button>
                                    </h2>
                                    <div id="collapseCustomPages" class="accordion-collapse collapse"
                                        aria-labelledby="headingCustomPages">
                                        <div class="accordion-body my-3">
                                            <ul class="m-0 p-0">
                                                <li>
                                                    <input type="checkbox" id="custom-page-1" class="mx-1"
                                                        data-url="services" data-name="Dịch vụ">
                                                    <label for="custom-page-1">Dịch vụ</label>
                                                </li>
                                            </ul>
                                            <button class="btn btn-primary mt-3" id="addCustomPagesToMenu">Thêm vào
                                                menu</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Danh sách Menu -->
                <div class="col-md-8 ">
                    <div class="card">
                        <div class="card-header">

                            <h5 class="card-title">Danh sách Menu</h5>
                        </div>
                        <div class="card-body">
                            <ul id="menuList" class="list-group accordion ">

                            </ul>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-primary mt-3 waves-effect waves-light" id="saveMenu">Lưu menu</button>
                        </div>
                    </div>
                </div>
            </div>



            <div class="drag-target"></div>
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
        document.addEventListener('DOMContentLoaded', function() {

            const menuList = document.getElementById('menuList');

            // Thêm liên kết tùy chỉnh vào menu
            const addCustomLinkButton = document.getElementById('addCustomLinkToMenu');
            if (addCustomLinkButton) {
                addCustomLinkButton.addEventListener('click', function() {
                    const url = document.getElementById('custom-link-url').value;
                    const text = document.getElementById('custom-link-text').value;
                    if (url && text) {
                        const menuItem = createMenuItem(url, text, 'custom-link');
                        menuList.appendChild(menuItem);
                        document.getElementById('custom-link-url').value = '';
                        document.getElementById('custom-link-text').value = '';
                        initializeNestedSortables(); // Khởi tạo lại sau khi thêm menu item
                    }
                });
            }

            // Thêm trang vào menu
            const addPagesButton = document.getElementById('addPagesToMenu');
            if (addPagesButton) {
                addPagesButton.addEventListener('click', function() {
                    const pages = document.querySelectorAll(
                        '#collapsePages input[type="checkbox"]:checked');
                    pages.forEach(page => {
                        const menuItem = createMenuItem(page.id, page.nextElementSibling
                            .textContent, 'page', page.dataset.url);
                        menuList.appendChild(menuItem);
                        page.checked = false;
                        initializeNestedSortables(); // Khởi tạo lại sau khi thêm menu item
                    });
                });
            }

            // Thêm danh mục vào menu
            const addCategoriesButton = document.getElementById('addCategoriesToMenu'); // Lấy nút thêm danh mục
            if (addCategoriesButton) {
                addCategoriesButton.addEventListener('click', function() {
                    const categories = document.querySelectorAll(
                        '#collapseCategories input[type="checkbox"]:checked'
                    ); // Lấy tất cả checkbox đã chọn
                    categories.forEach(category => {
                        const menuItem = createMenuItem(category.id, category.nextElementSibling
                            .textContent, 'category', category.dataset.url); // Tạo mục menu
                        menuList.appendChild(menuItem); // Thêm mục vào menu
                        category.checked = false; // Bỏ chọn checkbox
                        initializeNestedSortables(); // Khởi tạo lại sau khi thêm menu item
                    });
                });
            }

            // Cài đặt sự kiện cho nút thêm vào menu
            const addCustomPagesButton = document.getElementById('addCustomPagesToMenu');
            if (addCustomPagesButton) {
                addCustomPagesButton.addEventListener('click', function() {
                    const services = document.querySelectorAll(
                        '#collapseCustomPages input[type="checkbox"]');
                    services.forEach(service => {
                        const menuItem = createMenuItem(service.id, service.nextElementSibling
                            .textContent, 'service', service.dataset.url);
                        menuList.appendChild(menuItem);
                        service.checked = false; // Bỏ chọn checkbox
                        initializeNestedSortables(); // Khởi tạo lại sau khi thêm menu item
                    });
                });
            }

            function createMenuItem(id, text, type, url = "NULL") {
                const li = document.createElement('li');
                li.className = 'accordion-item nested-sortable';
                li.dataset.id = id;
                const content = type === 'custom-link' ?
                    `<div class="mb-3 my-3">
                        <label>Đường dẫn:</label>
                        <input type="text" class="form-control" value="${id}" id="inputUrl${id}">
                    </div>
                    <div class="mb-3 my-3">
                        <label>Văn bản:</label>
                        <input type="text" class="form-control" value="${text}" id="inputText${id}">
                    </div>` :
                    `<div class="mb-3 my-3">
                        <label>Văn bản:</label>
                        <input type="text" class="form-control" value="${text}" id="inputText${id}">
                    </div>`;
                li.innerHTML =
                    `<h2 class="accordion-header border" id="headingMenu${id}">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseMenu${id}" aria-expanded="false" aria-controls="collapseMenu${id}">
                            ${text}
                        </button>
                    </h2>
                    <div id="collapseMenu${id}" class=" border accordion-collapse collapse" aria-labelledby="headingMenu${id}">
                        <div class="accordion-body">
                            ${content}
                            <input type="hidden" class="form-control" value="${url}" id="inputUrl${id}">
                            <button class="btn btn-danger btn-sm mt-2" onclick="deleteMenuItem('${id}')">Xóa</button>
                            <button class="btn btn-primary btn-sm mt-2" onclick="updateMenuItem('${id}')">Cập nhật</button>
                        </div>
                    </div>
                    <ul class="nested-menu"></ul>`;
                menuList.appendChild(li); // Thêm vào menu chính
                return li;
            }

            window.updateMenuItem = function(id) {
                const newUrl = document.getElementById(`inputUrl${id}`)?.value || '';
                const newText = document.getElementById(`inputText${id}`).value;
                const headerButton = document.querySelector(`#headingMenu${id} .accordion-button`);
                if (headerButton) {
                    headerButton.innerText = newText;
                    if (newUrl) {
                        const item = document.querySelector(`li[data-id="${id}"]`);
                        if (item) {
                            item.dataset.id = newUrl;
                        }
                    }
                }
            }

            window.deleteMenuItem = function(id) {
                const item = document.querySelector(`li[data-id="${id}"]`);
                if (item) {
                    item.remove();
                }
            };


            function initializeNestedSortables() {
                const nestedSortables = document.querySelectorAll('#menuList, #menuList .nested-menu');
                nestedSortables.forEach(function(element) {
                    new Sortable(element, {
                        group: 'nested',
                        animation: 150,
                        fallbackOnBody: true,
                        swapThreshold: 0.65,
                        onEnd: function(evt) {
                            const targetMenu = evt.to;

                            // Kiểm tra các li trong menu để đảm bảo không có nhiều hơn 1 cấp sub-menu
                            const allItems = document.querySelectorAll(
                                '#menuList .accordion-item');
                            let hasNestedMenu = false;

                            allItems.forEach(function(item) {
                                const nestedUl = item.querySelector('ul.nested-menu');
                                if (nestedUl && nestedUl.children.length > 0) {
                                    // Nếu item có sub-menu, kiểm tra cấp độ của sub-menu
                                    const nestedItems = nestedUl.querySelectorAll('li');
                                    nestedItems.forEach(function(nestedItem) {
                                        const deeperUl = nestedItem
                                            .querySelector('ul.nested-menu');
                                        if (deeperUl && deeperUl.children
                                            .length > 0) {
                                            notifyMe('error',
                                                'Không thể tạo menu nhiều hơn 1 cấp'
                                            )
                                            evt.from.appendChild(evt
                                                .item
                                            ); // Trả lại item về vị trí cũ
                                            hasNestedMenu = true;
                                        }
                                    });
                                }
                            });

                            if (hasNestedMenu) {
                                return;
                            }
                        }
                    });
                });
            }


            initializeNestedSortables();
            const saveMenuButton = document.getElementById('saveMenu');
            if (saveMenuButton) {
                saveMenuButton.addEventListener('click', function() {
                    const menuData = getMenuData();
                    $.ajax({
                        url: "{{ route('admin.menu.saveMenu') }}",
                        method: 'POST',
                        data: {
                            menu: menuData,
                            _token: $('meta[name="csrf-token"]').attr('content') // CSRF token
                        },
                        success: function(data) {
                            if (data.success) {
                                notifyMe('success', data.message)
                            } else {
                                notifyMe('error', data.message)
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error('Lỗi:', errorThrown);
                        }
                    });

                });
            }

            function getMenuData() {
                const menuItems = [];
                const addedIds = new Set();

                const listItems = document.querySelectorAll('#menuList .accordion-item');

                listItems.forEach(item => {
                    const id = item.dataset.id;
                    const text = item.querySelector('.accordion-button').innerText;
                    const urlInput = item.querySelector(`input[id^="inputUrl"]`);
                    const urlValue = urlInput ? urlInput.value : null; // Lấy URL nếu có

                    // Nếu ID chưa được thêm, tạo đối tượng cho item chính
                    if (!addedIds.has(id)) {
                        const itemData = {
                            id: id,
                            text: text,
                            url: urlValue,
                            children: [] // Khởi tạo mảng cho sub-menu
                        };

                        menuItems.push(itemData); // Thêm vào danh sách menu
                        addedIds.add(id); // Đánh dấu ID đã được thêm

                        // Tìm kiếm sub-menu (nếu có)
                        const nestedItems = item.querySelectorAll('ul.nested-menu .accordion-item');
                        nestedItems.forEach(nestedItem => {
                            const nestedId = nestedItem.dataset.id;
                            const nestedText = nestedItem.querySelector('.accordion-button')
                                .innerText;
                            const nestedUrlInput = nestedItem.querySelector(
                                `input[id^="inputUrl"]`);
                            const nestedUrlValue = nestedUrlInput ? nestedUrlInput.value :
                                null; // Lấy URL của sub-menu

                            // Thêm dữ liệu của sub-menu vào children nếu chưa có
                            if (!addedIds.has(nestedId)) {
                                itemData.children.push({
                                    id: nestedId,
                                    text: nestedText,
                                    url: nestedUrlValue,
                                });
                                addedIds.add(nestedId); // Đánh dấu ID của sub-menu đã được thêm
                            }
                        });
                    }
                });

                // Bước 1: Tạo tập hợp các ID của children
                const childIds = new Set(menuItems.flatMap(item => item.children.map(child => child.id)));

                // Bước 2: Lọc menuItems để loại bỏ các mục có ID trong childIds
                const filteredMenuItems = menuItems.filter(item => !childIds.has(item.id));

                return filteredMenuItems;
            }


            $.ajax({
                url: "{{ route('admin.menu.getMenu') }}",
                method: 'GET',
                success: function(data) {
                    if (data) {
                        loadMenuData(data);
                        initializeNestedSortables(); // Khởi tạo lại sau khi load

                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Lỗi khi load menu:', errorThrown);
                }
            });

            function loadMenuData(menuData) {
                // Xử lý từng mục menu
                menuData.forEach(item => {
                    const menuItem = createMenuItem(item.id, item.text, 'custom-link', item.url);
                    menuList.appendChild(menuItem);
                    if (item.children && item.children.length > 0) {
                        loadSubMenu(menuItem.querySelector('.nested-menu'), item.children);
                    }
                });
            }

            function loadSubMenu(parentElement, children) {
                children.forEach(child => {
                    const subMenuItem = createMenuItem(child.id, child.text, 'custom-link', child.url);
                    parentElement.appendChild(subMenuItem);
                    if (child.children && child.children.length > 0) {
                        loadSubMenu(subMenuItem.querySelector('.nested-menu'), child.children);
                    }
                });
            }

        });
    </script>


    <script>
        $(document).ready(function() {

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
