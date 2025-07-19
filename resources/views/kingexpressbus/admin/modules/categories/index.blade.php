@extends('kingexpressbus.admin.layouts.main')
@section('title', 'Danh sách Danh mục')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Quản lý Danh mục Tin tức</h3>
        </div>
        <div class="card-body">
            @include('kingexpressbus.admin.partials.alerts')

            <div class="d-flex justify-content-between mb-3">
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tạo mới Danh mục
                </a>
                <form action="{{ route('admin.categories.index') }}" method="GET" class="form-inline">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Tìm theo tên danh mục..." value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table id="data-table-categories" class="table table-bordered table-striped table-hover">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên Danh mục</th>
                        <th>Slug</th>
                        <th>Số lượng tin tức</th>
                        <th>Hành động</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->slug }}</td>
                            <td>{{ $category->count }}</td>
                            <td>
                                <a class="btn btn-warning btn-sm" href="{{ route('admin.categories.edit', ['category' => $category->id]) }}">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                                <form action="{{ route('admin.categories.destroy', ['category' => $category->id]) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa Danh mục này?')">
                                        <i class="fas fa-trash"></i> Xoá
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Không tìm thấy dữ liệu phù hợp.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3 d-flex justify-content-end">
                {{ $categories->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#data-table-categories').DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "paging": false,
                "searching": false,
                "ordering": false,
                "info": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            }).buttons().container().appendTo('#data-table-categories_wrapper .col-md-6:eq(0)');
        });
    </script>
@endpush