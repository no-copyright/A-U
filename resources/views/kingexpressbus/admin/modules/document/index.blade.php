@extends('kingexpressbus.admin.layouts.main')
@section('title', 'Danh sách Tài liệu')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Quản lý Tài liệu</h3>
    </div>
    <div class="card-body">
        @include('kingexpressbus.admin.partials.alerts')

        <a href="{{ route('admin.documents.create') }}" class="btn btn-primary mb-3">
            <i class="fas fa-plus"></i> Thêm mới Tài liệu
        </a>

        <table id="data-table-documents" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên Tài liệu</th>
                    <th>Đường dẫn File</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $document)
                <tr>
                    <td>{{ $document->id }}</td>
                    <td>{{ $document->name }}</td>
                    <td><a href="{{ $document->src }}" target="_blank">{{ $document->src }}</a></td>
                    <td>
                        <a class="btn btn-warning btn-sm"
                            href="{{ route('admin.documents.edit', ['document' => $document->id]) }}">
                            <i class="fas fa-edit"></i> Sửa
                        </a>
                        <form action="{{ route('admin.documents.destroy', ['document' => $document->id]) }}"
                            method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Bạn có chắc muốn xóa tài liệu này?')">
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
        $('#data-table-documents').DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "language": { "url": "/admin/plugins/datatables/Vietnamese.json" },
            "order": [[1, "asc"]]
        }).buttons().container().appendTo('#data-table-documents_wrapper .col-md-6:eq(0)');
    });
</script>
@endpush