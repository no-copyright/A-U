<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
    data-accordion="false">

    <!-- ADMIN BASE -->
    <li class="nav-header">Admin</li>
    
    {{-- Mục này giữ nguyên --}}
    <x-menus.menu-bar :route="route('admin.dashboard.index')" name="Cấu hình chung" icon="fas fa-tachometer-alt">
        <x-menus.menu-item :route="route('admin.dashboard.index')" name="Thông tin Website"></x-menus.menu-item>
    </x-menus.menu-bar>

    {{-- Mục này giữ nguyên --}}
    <x-menus.menu-bar icon="fas fa-map-marked-alt" name="Quản lý Tin tức" route="#">
        <x-menus.menu-item name="Danh mục Tin tức" :route="route('admin.categories.index')"/>
        <x-menus.menu-item name="Danh sách Tin tức" :route="route('admin.news.index')"/>
    </x-menus.menu-bar>

    {{-- Mục này giữ nguyên --}}
    <x-menus.menu-bar icon="fas fa-route" name="Quản lý Đào tạo" route="#">
        <x-menus.menu-item name="Khoá Đào tạo" :route="route('admin.training.index')"/>
        <x-menus.menu-item name="Danh sách Giáo viên" :route="route('admin.teachers.index')"/>
    </x-menus.menu-bar>

    {{-- Mục này giữ nguyên --}}
    <x-menus.menu-bar icon="fas fa-ticket-alt" name="Quản lý Khách hàng" route="#">
        <x-menus.menu-item name="Đăng ký tư vấn" :route="route('admin.customers.index')"/>
    </x-menus.menu-bar>

    {{-- XÓA HOẶC COMMENT OUT TOÀN BỘ KHỐI NÀY --}}
    {{--
    <x-menus.menu-bar icon="fas fa-list" name="Quản lý Menu" route="{{ route('admin.menus.index') }}">
        <x-menus.menu-item name="Danh sách Menu" :route="route('admin.menus.index')"/>
    </x-menus.menu-bar>
    --}}
    
</ul>