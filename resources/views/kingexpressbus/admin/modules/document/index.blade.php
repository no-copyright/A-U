@extends('kingexpressbus.admin.layouts.main')
@section('title', 'Danh sách Tài liệu')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Quản lý Tài liệu</h3>
    </div>
    <div class="card-body">
        @include('kingexpressbus.admin.partials.alerts')

        <div class="d-flex justify-content-between mb-3">
            <a href="{{ route('admin.documents.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm mới Tài liệu
            </a>
            <form action="{{ route('admin.documents.index') }}" method="GET" class="form-inline">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Tìm theo tên tài liệu..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table id="data-table-documents" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ưu tiên</th>
                        <th>Tên Tài liệu</th>
                        <th>Đường dẫn File</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $document)
                    <tr>
                        <td>{{ $document->id }}</td>
                        <td>{{ $document->priority }}</td>
                        <td>{{ $document->name }}</td>
                        <td><a href="{{ url($document->src) }}" target="_blank">{{ $document->src }}</a></td>
                        <td>
                            <a class="btn btn-warning btn-sm" href="{{ route('admin.documents.edit', ['document' => $document->id]) }}">
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                            <form action="{{ route('admin.documents.destroy', ['document' => $document->id]) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa tài liệu này?')">
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
            {{ $documents->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#data-table-documents').DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "paging": false,
            "searching": false,
            "ordering": false,
            "info": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
        }).buttons().container().appendTo('#data-table-documents_wrapper .col-md-6:eq(0)');
    });
</script>
@endpush