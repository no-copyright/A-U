@extends('kingexpressbus.admin.layouts.main')
@section('title', 'Danh sách Giáo viên')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Quản lý Giáo viên</h3>
    </div>
    <div class="card-body">
        @include('kingexpressbus.admin.partials.alerts')

        <div class="d-flex justify-content-between mb-3">
            <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm mới Giáo viên
            </a>
            <form action="{{ route('admin.teachers.index') }}" method="GET" class="form-inline">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Tìm theo tên giáo viên..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table id="data-table-teachers" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ưu tiên</th>
                        <th>Ảnh đại diện</th>
                        <th>Họ và tên</th>
                        <th>Quốc tịch</th>
                        <th>Email</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $teacher)
                    <tr>
                        <td>{{ $teacher->id }}</td>
                        <td>{{ $teacher->priority }}</td>
                        <td>
                            @if($teacher->avatar)
                            <img src="{{ url($teacher->avatar) }}" alt="{{ $teacher->full_name }}" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
                            @endif
                        </td>
                        <td>{{ $teacher->full_name }}</td>
                        <td>{{ $teacher->role }}</td>
                        <td>{{ $teacher->email }}</td>
                        <td>
                            <a class="btn btn-warning btn-sm" href="{{ route('admin.teachers.edit', ['teacher' => $teacher->id]) }}">
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                            <form action="{{ route('admin.teachers.destroy', ['teacher' => $teacher->id]) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa Giáo viên này?')">
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
            {{ $teachers->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#data-table-teachers').DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "paging": false,
            "searching": false,
            "ordering": false,
            "info": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
        }).buttons().container().appendTo('#data-table-teachers_wrapper .col-md-6:eq(0)');
    });
</script>
@endpush