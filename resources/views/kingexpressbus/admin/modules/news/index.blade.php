@extends('kingexpressbus.admin.layouts.main')
@section('title', 'Danh sách Tin tức')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Quản lý Tin tức</h3>
    </div>
    <div class="card-body">
        @include('kingexpressbus.admin.partials.alerts')

        <div class="d-flex justify-content-between mb-3">
            <a href="{{ route('admin.news.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tạo mới Tin tức
            </a>
            <form action="{{ route('admin.news.index') }}" method="GET" class="form-inline">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Tìm theo tiêu đề..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table id="data-table-news" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tiêu đề</th>
                        <th>Ảnh đại diện</th>
                        <th>Mô tả ngắn</th>
                        <th>Danh mục</th>
                        <th>Lượt xem</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($newsItems as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->title }}</td>
                        <td>
                            @if($item->thumbnail)
                            <img src="{{ url($item->thumbnail) }}" alt="{{ $item->title }}" style="max-width: 100px; max-height: 100px;">
                            @endif
                        </td>
                        <td>{{ Str::limit($item->excerpt, 100) }}</td>
                        <td>{{ $item->category_name ?? 'N/A' }}</td>
                        <td>{{ $item->view }}</td>
                        <td>
                            <a class="btn btn-warning btn-sm" href="{{ route('admin.news.edit', ['news' => $item->id]) }}">
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                            <form action="{{ route('admin.news.destroy', ['news' => $item->id]) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa Tin tức này?')">
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
            {{ $newsItems->appends(request()->query())->links() }}
        </div>
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
            "paging": false,
            "searching": false,
            "ordering": false,
            "info": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
        }).buttons().container().appendTo('#data-table-news_wrapper .col-md-6:eq(0)');
    });
</script>
@endpush