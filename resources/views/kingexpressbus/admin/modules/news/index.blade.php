@extends('kingexpressbus.admin.layouts.main')
@section('title', 'Danh sách Tin tức')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Quản lý Tin tức</h3>
    </div>
    <div class="card-body">
        @include('kingexpressbus.admin.partials.alerts')

        <a href="{{ route('admin.news.create') }}" class="btn btn-primary mb-3">
            <i class="fas fa-plus"></i> Tạo mới Tin tức
        </a>

        <table id="data-table-news" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tiêu đề</th>
                    <th>Ảnh đại diện</th>
                    <th>Danh mục</th>
                    <th>Tác giả</th>
                    <th>Lượt xem</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($newsItems as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->title }}</td>
                    <td>
                        @if($item->thumbnail)
                        <img src="{{ $item->thumbnail }}" alt="{{ $item->title }}"
                            style="max-width: 100px; max-height: 100px;">
                        @endif
                    </td>
                    <td>{{ $item->category_name ?? 'N/A' }}</td>
                    <td>{{ $item->author }}</td>
                    <td>{{ $item->view }}</td>
                    <td>
                        <a class="btn btn-warning btn-sm"
                            href="{{ route('admin.news.edit', ['news' => $item->id]) }}">
                            <i class="fas fa-edit"></i> Sửa
                        </a>
                        <form action="{{ route('admin.news.destroy', ['news' => $item->id]) }}"
                            method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Bạn có chắc muốn xóa Tin tức này?')">
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
        $('#data-table-news').DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "language": {
                "url": "/admin/plugins/datatables/Vietnamese.json"
            },
            "order": [
                [0, "desc"]
            ]
        }).buttons().container().appendTo('#data-table-news_wrapper .col-md-6:eq(0)');
    });
</script>
@endpush