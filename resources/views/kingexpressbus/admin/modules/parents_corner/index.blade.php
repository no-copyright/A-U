@extends('kingexpressbus.admin.layouts.main')
@section('title', 'Đánh giá của Phụ huynh')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Quản lý Đánh giá của Phụ huynh</h3>
    </div>
    <div class="card-body">
        @include('kingexpressbus.admin.partials.alerts')

        <a href="{{ route('admin.parents-corner.create') }}" class="btn btn-primary mb-3">
            <i class="fas fa-plus"></i> Tạo mới Đánh giá
        </a>

        <table id="data-table-reviews" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ảnh</th>
                    <th>Tên Phụ huynh</th>
                    <th>Slug</th>
                    <th>Chức danh/Mô tả</th>
                    <th>Đánh giá (rate)</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reviews as $review)
                <tr>
                    <td>{{ $review->id }}</td>
                    <td><img src="{{ $review->image }}" alt="Ảnh" style="max-width: 80px; border-radius: 50%;"></td>
                    <td>{{ $review->name }}</td>
                    <td>{{ $review->slug }}</td>
                    <td>{{ $review->describe }}</td>
                    <td>{{ $review->rate }}</td>
                    <td>
                        <a class="btn btn-warning btn-sm"
                            href="{{ route('admin.parents-corner.edit', ['parents_corner' => $review->id]) }}">
                            <i class="fas fa-edit"></i> Sửa
                        </a>
                        <form action="{{ route('admin.parents-corner.destroy', ['parents_corner' => $review->id]) }}"
                            method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Bạn có chắc muốn xóa đánh giá này?')">
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
<script>
    $(document).ready(function() {
        $('#data-table-reviews').DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "language": { "url": "/admin/plugins/datatables/Vietnamese.json" },
            "order": [[0, "desc"]]
        }).buttons().container().appendTo('#data-table-reviews_wrapper .col-md-6:eq(0)');
    });
</script>
@endpush