<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
    data-accordion="false">

    <!-- ADMIN BASE -->
    <li class="nav-header">Admin</li>
    
    {{-- THAY ĐỔI MỤC NÀY: Trỏ thẳng đến trang quản lý giao diện Trang chủ --}}
    <x-menus.menu-bar :route="route('admin.dashboard.index')" name="Quản lý Trang chủ" icon="fas fa-home">
    </x-menus.menu-bar>

    <x-menus.menu-bar icon="fa-regular fa-newspaper" name="Quản lý Tin tức" route="#">
        <x-menus.menu-item name="Danh mục Tin tức" :route="route('admin.categories.index')"/>
        <x-menus.menu-item name="Danh sách Tin tức" :route="route('admin.news.index')"/>
    </x-menus.menu-bar>

    <x-menus.menu-bar icon="fas fa-graduation-cap" name="Quản lý Đào tạo" route="#">
        <x-menus.menu-item name="Khoá Đào tạo" :route="route('admin.training.index')"/>
        <x-menus.menu-item name="Danh sách Giáo viên" :route="route('admin.teachers.index')"/>
    </x-menus.menu-bar>

    <x-menus.menu-bar icon="fas fa-address-book" name="Quản lý Khách hàng" route="#">
        <x-menus.menu-item name="Đăng ký tư vấn" :route="route('admin.customers.index')"/>
    </x-menus.menu-bar>
    
</ul>