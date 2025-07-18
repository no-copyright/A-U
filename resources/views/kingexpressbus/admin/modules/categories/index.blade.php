@extends('kingexpressbus.admin.layouts.main')
@section('title', 'Danh sách Danh mục')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Quản lý Danh mục Tin tức</h3>
        </div>
        <div class="card-body">
            @include('kingexpressbus.admin.partials.alerts')

            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary mb-3">
                <i class="fas fa-plus"></i> Tạo mới Danh mục
            </a>

            <table id="data-table-categories" class="table table-bordered table-striped table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên Danh mục</th>
                    <th>Số lượng tin tức</th>
                    <th>Hành động</th>
                </tr>
                </thead>
                <tbody>
                @foreach($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->count }}</td>
                        <td>
                            <a class="btn btn-warning btn-sm"
                               href="{{ route('admin.categories.edit', ['category' => $category->id]) }}">
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                            <form action="{{ route('admin.categories.destroy', ['category' => $category->id]) }}"
                                  method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Bạn có chắc muốn xóa Danh mục này?')">
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
            $('#data-table-categories').DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                "language": {"url": "/admin/plugins/datatables/Vietnamese.json"},
                "order": [[1, "asc"]]
            }).buttons().container().appendTo('#data-table-categories_wrapper .col-md-6:eq(0)');
        });
    </script>
@endpush