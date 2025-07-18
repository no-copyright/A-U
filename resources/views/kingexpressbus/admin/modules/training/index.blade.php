@extends('kingexpressbus.admin.layouts.main')
@section('title', 'Danh sách Khoá Đào tạo')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Quản lý Khoá Đào tạo</h3>
    </div>
    <div class="card-body">
        {{-- Include file hiển thị thông báo (thành công, lỗi) --}}
        @include('kingexpressbus.admin.partials.alerts')

        {{-- Nút để điều hướng đến trang tạo mới --}}
        <a href="{{ route('admin.training.create') }}" class="btn btn-primary mb-3">
            <i class="fas fa-plus"></i> Tạo mới Khoá Đào tạo
        </a>

        {{-- Bảng hiển thị dữ liệu --}}
        <table id="data-table-training" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tiêu đề</th>
                    <th>Ảnh đại diện</th>
                    <th>Lứa tuổi</th>
                    <th>Thời lượng</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                {{-- Vòng lặp để duyệt qua biến $trainings được truyền từ Controller --}}
                @foreach($trainings as $training)
                <tr>
                    <td>{{ $training->id }}</td>
                    <td>{{ $training->title }}</td>
                    <td>
                        @if($training->thumbnail)
                        <img src="{{ $training->thumbnail }}" alt="{{ $training->title }}"
                            style="max-width: 100px; max-height: 100px;">
                        @else
                        N/A
                        @endif
                    </td>
                    <td>{{ $training->age }}</td>
                    <td>{{ $training->duration }}</td>
                    <td>
                        {{-- Nút Sửa --}}
                        <a class="btn btn-warning btn-sm"
                            href="{{ route('admin.training.edit', ['training' => $training->id]) }}">
                            <i class="fas fa-edit"></i> Sửa
                        </a>

                        {{-- Nút Xóa được đặt trong một form --}}
                        <form action="{{ route('admin.training.destroy', ['training' => $training->id]) }}"
                            method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Bạn có chắc chắn muốn xóa Khoá Đào tạo này? Hành động này không thể hoàn tác.')">
                                <i class="fas fa-trash"></i> Xoá
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
{{-- Script để khởi tạo DataTable, giúp bảng có thêm tính năng tìm kiếm, sắp xếp, phân trang phía client --}}
<script>
    $(document).ready(function() {
        $('#data-table-training').DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "language": {
                "url": "/admin/plugins/datatables/Vietnamese.json" // Sử dụng file ngôn ngữ Tiếng Việt cho DataTable
            },
            "order": [
                [0, "desc"]
            ] // Sắp xếp mặc định theo ID giảm dần
        }).buttons().container().appendTo('#data-table-training_wrapper .col-md-6:eq(0)');
    });
</script>
@endpush