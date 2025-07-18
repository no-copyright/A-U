@extends('kingexpressbus.admin.layouts.main')
@section('title', 'Danh sách Giáo viên')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Quản lý Giáo viên</h3>
        </div>
        <div class="card-body">
            @include('kingexpressbus.admin.partials.alerts')

            <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary mb-3">
                <i class="fas fa-plus"></i> Thêm mới Giáo viên
            </a>

            <table id="data-table-teachers" class="table table-bordered table-striped table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Ảnh đại diện</th>
                    <th>Họ và tên</th>
                    <th>Vai trò</th>
                    <th>Slug</th> {{-- <--- THÊM CỘT MỚI --}}
                    <th>Email</th>
                    <th>Hành động</th>
                </tr>
                </thead>
                <tbody>
                @foreach($teachers as $teacher)
                    <tr>
                        <td>{{ $teacher->id }}</td>
                        <td>
                             @if($teacher->avatar)
                                <img src="{{ $teacher->avatar }}" alt="{{ $teacher->full_name }}"
                                     style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
                            @endif
                        </td>
                        <td>{{ $teacher->full_name }}</td>
                        <td>{{ $teacher->role }}</td>
                        <td>{{ $teacher->slug }}</td> {{-- <--- THÊM DỮ LIỆU CỘT MỚI --}}
                        <td>{{ $teacher->email }}</td>
                        <td>
                            <a class="btn btn-warning btn-sm"
                               href="{{ route('admin.teachers.edit', ['teacher' => $teacher->id]) }}">
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                            <form action="{{ route('admin.teachers.destroy', ['teacher' => $teacher->id]) }}"
                                  method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Bạn có chắc muốn xóa Giáo viên này?')">
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
        $(document).ready(function () {
            $('#data-table-teachers').DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                "language": {"url": "/admin/plugins/datatables/Vietnamese.json"},
                "order": [[2, "asc"]] // Sắp xếp theo tên
            }).buttons().container().appendTo('#data-table-teachers_wrapper .col-md-6:eq(0)');
        });
    </script>
@endpush