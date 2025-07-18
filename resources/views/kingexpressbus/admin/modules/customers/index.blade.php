@extends('kingexpressbus.admin.layouts.main')
@section('title', 'Danh sách Khách hàng đăng ký')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Quản lý Khách hàng đăng ký</h3>
        </div>
        <div class="card-body">
            @include('kingexpressbus.admin.partials.alerts')

            <table id="data-table-customers" class="table table-bordered table-striped table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên Phụ huynh</th>
                    <th>Điện thoại</th>
                    <th>Tên Học viên</th>
                    <th>Khóa học đăng ký</th>
                    <th>Ngày đăng ký</th>
                    <th>Hành động</th>
                </tr>
                </thead>
                <tbody>
                @foreach($customers as $customer)
                    <tr>
                        <td>{{ $customer->id }}</td>
                        <td>{{ $customer->full_name_parent }}</td>
                        <td>{{ $customer->phone }}</td>
                        <td>{{ $customer->full_name_children }}</td>
                        <td>{{ $customer->training_title ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($customer->created_at)->format('d/m/Y H:i') }}</td>
                        <td>
                            {{-- THÊM NÚT "XEM" Ở ĐÂY --}}
                            <a class="btn btn-info btn-sm"
                               href="{{ route('admin.customers.show', ['customer' => $customer->id]) }}" title="Xem chi tiết">
                                <i class="fas fa-eye"></i> Xem
                            </a>
                            
                            <form action="{{ route('admin.customers.destroy', ['customer' => $customer->id]) }}"
                                  method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Bạn có chắc muốn xóa đăng ký này?')">
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
    {{-- Script không thay đổi --}}
    <script>
        $(document).ready(function () {
            $('#data-table-customers').DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                "language": {"url": "/admin/plugins/datatables/Vietnamese.json"},
                "order": [[5, "desc"]] 
            }).buttons().container().appendTo('#data-table-customers_wrapper .col-md-6:eq(0)');
        });
    </script>
@endpush