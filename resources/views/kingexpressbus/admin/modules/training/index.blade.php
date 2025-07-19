@extends('kingexpressbus.admin.layouts.main')
@section('title', 'Danh sách Khoá Đào tạo')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Quản lý Khoá Đào tạo</h3>
    </div>
    <div class="card-body">
        @include('kingexpressbus.admin.partials.alerts')

        <div class="d-flex justify-content-between mb-3">
            <a href="{{ route('admin.training.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tạo mới Khoá Đào tạo
            </a>
            <form action="{{ route('admin.training.index') }}" method="GET" class="form-inline">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Tìm theo tiêu đề..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table id="data-table-training" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ưu tiên</th>
                        <th>Tiêu đề</th>
                        <th>Ảnh đại diện</th>
                        <th>Lứa tuổi</th>
                        <th>Thời lượng</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($trainings as $training)
                    <tr>
                        <td>{{ $training->id }}</td>
                        <td>{{ $training->priority }}</td>
                        <td>{{ $training->title }}</td>
                        <td>
                            @if($training->thumbnail)
                            <img src="{{ url($training->thumbnail) }}" alt="{{ $training->title }}" style="max-width: 100px; max-height: 100px;">
                            @endif
                        </td>
                        <td>{{ $training->age }}</td>
                        <td>{{ $training->duration }}</td>
                        <td>
                            <a class="btn btn-warning btn-sm" href="{{ route('admin.training.edit', ['training' => $training->id]) }}">
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                            <form action="{{ route('admin.training.destroy', ['training' => $training->id]) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa Khoá Đào tạo này?')">
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
            {{ $trainings->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#data-table-training').DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "paging": false,
            "searching": false,
            "ordering": false,
            "info": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
        }).buttons().container().appendTo('#data-table-training_wrapper .col-md-6:eq(0)');
    });
</script>
@endpush