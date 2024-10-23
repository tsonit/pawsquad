<!doctype html>
<html>

<head>
    <link rel="stylesheet" href="{{ asset('assets/builderjs/builder.css') }}">
    </link>
    <link rel="stylesheet" href="{{ asset('assets/clients/css/style.css') }}">
</link>
    <script type='text/javascript' src="{{ asset('assets/builderjs/builder.js') }}"></script>
    <style>
        .lds-dual-ring {
            display: inline-block;
            width: 80px;
            height: 80px;
        }

        .lds-dual-ring:after {
            content: " ";
            display: block;
            width: 30px;
            height: 30px;
            margin: 4px;
            border-radius: 80%;
            border: 2px solid #aaa;
            border-color: #007bff transparent #007bff transparent;
            animation: lds-dual-ring 1.2s linear infinite;
        }

        @keyframes lds-dual-ring {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .design-new,
        .design-clear,
        .design-from-template,
        .design-upload-template {
            display: none !important;
        }
    </style>
    <script>
        var params = new URLSearchParams(window.location.search);
        var tags = [{
            type: 'label',
            tag: 'SUBSCRIBER_NAME'
        }, {
            type: 'label',
            tag: 'WEB_NAME'
        }, {
            type: 'label',
            tag: 'SUBSCRIBER_EMAIL'
        }];

        var builder = new Editor({
            root: "{{ asset('assets/builderjs/') }}",
            showInlineToolbar: true,
            urlBack: "{{ route('admin.subscribers.theme') }}",
            backUrl: "{{ route('admin.subscribers.theme') }}",
            uploadAssetUrl: "{{ route('admin.subscribers.uploadAsset') }}",
            uploadAssetMethod: 'POST',
            saveUrl: "{{ route('admin.subscribers.editSubscriberTheme', ['id' => request()->id]) }}",
            saveMethod: 'POST',
            url: "{{ route('admin.subscribers.getTheme', ['id' => request()->id]) }}",
            tags: tags,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: {
                _token: '{{ csrf_token() }}',
                template_id: '{{ request()->id }}'
            },
            workplace: "{{ request()->id }}",
            disableFeatures: ['change_template', 'design-upload-template', 'export', 'footer_exit', 'help'],
            changeTemplateCallback: function(url) {
                window.location = url;
            },
        });
        $(document).ready(function() {
            builder.init();
            const elementsToRemove = document.querySelectorAll(
                'li a.design-new, li a.design-clear, li a.design-from-template, li a.design-upload-template'
            );

            elementsToRemove.forEach(function(link) {
                link.parentElement.remove();
            });
        });
    </script>

</head>

<body class="overflow-hidden">
    <div
        style="text-align: center;
            height: 100vh;
            vertical-align: middle;
            padding: auto;
            display: flex;">
        <div style="margin:auto" class="lds-dual-ring"></div>
    </div>

    <script>
        switch (window.location.protocol) {
            case 'http:':
            case 'https:':
                //remote file over http or https
                break;
            case 'file:':
                alert('Please put the builderjs/ folder into your document root and open it through a web URL');
                window.location.href = "./index.php";
                break;
            default:
                //some other protocol
        }
    </script>
</body>

</html>
