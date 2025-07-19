<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
    data-accordion="false">

    <!-- ADMIN BASE -->
    <li class="nav-header">Admin</li>

    <x-menus.menu-bar :route="route('admin.dashboard.index')" name="Quản lý Trang chủ" icon="fas fa-home">
    </x-menus.menu-bar>

    <x-menus.menu-bar icon="fa-regular fa-newspaper" name="Quản lý Tin tức" route="#">
        <x-menus.menu-item name="Danh mục Tin tức" :route="route('admin.categories.index')" />
        <x-menus.menu-item name="Danh sách Tin tức" :route="route('admin.news.index')" />
    </x-menus.menu-bar>

    <x-menus.menu-bar icon="fas fa-graduation-cap" name="Quản lý Đào tạo" route="#">
        <x-menus.menu-item name="Khoá Đào tạo" :route="route('admin.training.index')" />
        <x-menus.menu-item name="Danh sách Giáo viên" :route="route('admin.teachers.index')" />
    </x-menus.menu-bar>

    <x-menus.menu-bar :route="route('admin.customers.index')" name="Quản lý Khách hàng" icon="fas fa-address-book" />

    <!-- THÊM MỚI CÁC MENU TẠI ĐÂY -->
    <li class="nav-header">Cài đặt & Khác</li>

    <x-menus.menu-bar :route="route('admin.contact.index')" name="Quản lý Liên hệ" icon="fas fa-address-card">
    </x-menus.menu-bar>

    <x-menus.menu-bar :route="route('admin.parents-corner.index')" name="Đánh giá Phụ huynh" icon="fas fa-comments">
    </x-menus.menu-bar>

    <x-menus.menu-bar :route="route('admin.documents.index')" name="Quản lý Tài liệu" icon="fas fa-folder-open">
    </x-menus.menu-bar>
    <!-- KẾT THÚC PHẦN THÊM MỚI -->

</ul>