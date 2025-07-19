@extends('kingexpressbus.admin.layouts.main')
@section('title', 'Đánh giá của Phụ huynh')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Quản lý Đánh giá của Phụ huynh</h3>
    </div>
    <div class="card-body">
        @include('kingexpressbus.admin.partials.alerts')

        <div class="d-flex justify-content-between mb-3">
            <a href="{{ route('admin.parents-corner.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tạo mới Đánh giá
            </a>
            <form action="{{ route('admin.parents-corner.index') }}" method="GET" class="form-inline">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Tìm theo tên phụ huynh..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table id="data-table-reviews" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ưu tiên</th>
                        <th>Ảnh</th>
                        <th>Tên Phụ huynh</th>
                        <th>Chức danh/Mô tả</th>
                        <th>Đánh giá (rate)</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                    <tr>
                        <td>{{ $review->id }}</td>
                        <td>{{ $review->priority }}</td>
                        <td><img src="{{ url($review->image) }}" alt="Ảnh" style="max-width: 80px; border-radius: 50%;"></td>
                        <td>{{ $review->name }}</td>
                        <td>{{ $review->describe }}</td>
                        <td>{{ $review->rate }}</td>
                        <td>
                            <a class="btn btn-warning btn-sm" href="{{ route('admin.parents-corner.edit', ['parents_corner' => $review->id]) }}">
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                            <form action="{{ route('admin.parents-corner.destroy', ['parents_corner' => $review->id]) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa đánh giá này?')">
                                    <i class="fas fa-trash"></i> Xoá
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Không tìm thấy dữ liệu phù hợp.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3 d-flex justify-content-end">
            {{ $reviews->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#data-table-reviews').DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "paging": false,
            "searching": false,
            "ordering": false,
            "info": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
        }).buttons().container().appendTo('#data-table-reviews_wrapper .col-md-6:eq(0)');
    });
</script>
@endpush