<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield("title")</title>
    @include('kingexpressbus.admin.components.styles')
    @stack('styles')

    <style>
        .image-preview-grid .list-unstyled {
            gap: 15px;
        }
        .image-preview-grid .grid-item {
            position: relative;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
            width: 150px;
            height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
        }
        .image-preview-grid .grid-item img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }
        .image-preview-grid .grid-item .btn-remove-image {
            position: absolute;
            top: -10px;
            right: -10px;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            line-height: 1;
        }

        #stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .stat-item {
            border: 1px solid #dee2e6 !important;
            border-radius: .25rem !important;
            padding: 1.25rem !important;
            margin-bottom: 0 !important;
            display: flex;
            flex-direction: column;
        }
        .stat-item .mt-2 {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .stat-item img {
            border-radius: 4px;
            border: 1px solid #ddd;
            padding: 3px;
            background: #fff;
        }
        .fag-item {
             display: flex;
             flex-direction: column;
             border: 1px solid #dee2e6 !important;
             border-radius: .25rem !important;
             padding: 1.25rem !important;
             margin-bottom: 1rem !important;
        }
        .stat-item .remove-item, .fag-item .remove-item {
            margin-top: auto;
            align-self: center;
        }
        .stat-item hr, .fag-item hr {
            display: none;
        }

        #templates .stat-item, #templates .fag-item {
            display: block; 
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    @include('kingexpressbus.admin.components.navbar')

    @include('kingexpressbus.admin.components.sidebar')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>@yield('title')</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active">@yield('title')</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                @yield("content")
            </div>
        </section>
    </div>
    <footer class="main-footer">
        <div class="float-right d-none d-sm-block">
            <b>Version</b> 3.2.0
        </div>
        <strong>Copyright © 2025 <a href="#">{{config("app.name")}}</a>.</strong>
    </footer>
    <aside class="control-sidebar control-sidebar-dark">
    </aside>
</div>

<!-- CK Editor and CK Finder set up -->
@include('ckfinder::setup')
@include('kingexpressbus.admin.components.scripts')
@stack('scripts')
</body>
</html>