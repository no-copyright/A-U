@extends('kingexpressbus.admin.layouts.main')
@section('title', 'Danh sách Khách hàng đăng ký')

{{-- Thêm CSS tùy chỉnh cho dropdown trạng thái --}}
@push('styles')
<style>
    .status-dropdown {
        font-weight: bold; /* Chữ đậm hơn */
        font-size: 0.9rem; /* Chữ to hơn một chút */
        padding-top: .25rem;
        padding-bottom: .25rem;
        line-height: 1.5;
        vertical-align: middle; /* Căn giữa theo chiều dọc */
    }
    /* Đảm bảo option cũng có màu nền trên một số trình duyệt */
    .status-dropdown option {
        background-color: #fff;
        color: #000;
    }
</style>
@endpush

@section('content')
    
    {{-- FORM LỌC MỚI --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Bộ lọc</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.customers.index') }}" method="GET" class="form-inline">
                <div class="form-group mr-3 mb-2">
                    <label for="status-filter" class="mr-2">Trạng thái:</label>
                    <select name="status" id="status-filter" class="form-control form-control-sm">
                        <option value="">-- Tất cả --</option>
                        <option value="pending" @selected(request('status') == 'pending')>Chờ xử lý</option>
                        <option value="confirmed" @selected(request('status') == 'confirmed')>Đã xác nhận</option>
                        <option value="cancelled" @selected(request('status') == 'cancelled')>Đã hủy</option>
                    </select>
                </div>

                <div class="form-group mr-3 mb-2">
                    <label for="date-range-filter" class="mr-2">Ngày đăng ký:</label>
                    <input type="text" name="date_range" id="date-range-filter" class="form-control form-control-sm" value="{{ request('date_range') }}" placeholder="Chọn khoảng ngày">
                </div>

                <button type="submit" class="btn btn-primary btn-sm mb-2 mr-2">
                    <i class="fas fa-filter"></i> Lọc
                </button>
                <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary btn-sm mb-2">
                    <i class="fas fa-times"></i> Xóa lọc
                </a>
            </form>
        </div>
    </div>


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
                    <th>Khóa học</th>
                    <th>Ngày đăng ký</th>
                    <th>Trạng thái</th>
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
                            @php
                                $statusConfig = [
                                    'pending' => ['text' => 'Chờ xử lý', 'class' => 'bg-warning'],
                                    'confirmed' => ['text' => 'Đã xác nhận', 'class' => 'bg-success'],
                                    'cancelled' => ['text' => 'Đã hủy', 'class' => 'bg-danger'],
                                ];
                            @endphp
                            
                            <form action="{{ route('admin.customers.updateStatus', ['customer' => $customer->id]) }}" method="POST">
                                @csrf
                                {{-- Thêm class 'status-dropdown' để áp dụng CSS --}}
                                <select name="status" 
                                        class="form-control form-control-sm text-white status-dropdown {{ $statusConfig[$customer->status]['class'] ?? 'bg-secondary' }}" 
                                        onchange="this.form.submit()">
                                    
                                    @foreach ($statusConfig as $key => $value)
                                        <option value="{{ $key }}" 
                                                @selected($customer->status == $key) 
                                                class="{{ $value['class'] }}">
                                            {{ $value['text'] }}
                                        </option>
                                    @endforeach

                                </select>
                            </form>
                        </td>

                        <td>
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
    {{-- Script cho Date Range Picker --}}
    <script>
        $(function() {
            $('#date-range-filter').daterangepicker({
                autoUpdateInput: false, // Không tự động điền ngày khi mới mở
                locale: {
                    "format": "DD/MM/YYYY",
                    "separator": " - ",
                    "applyLabel": "Áp dụng",
                    "cancelLabel": "Hủy",
                    "fromLabel": "Từ",
                    "toLabel": "Đến",
                    "customRangeLabel": "Tùy chọn",
                    "weekLabel": "T",
                    "daysOfWeek": ["CN", "T2", "T3", "T4", "T5", "T6", "T7"],
                    "monthNames": ["Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6", "Tháng 7", "Tháng 8", "Tháng 9", "Tháng 10", "Tháng 11", "Tháng 12"],
                    "firstDay": 1
                },
                ranges: {
                   'Hôm nay': [moment(), moment()],
                   'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                   '7 ngày qua': [moment().subtract(6, 'days'), moment()],
                   '30 ngày qua': [moment().subtract(29, 'days'), moment()],
                   'Tháng này': [moment().startOf('month'), moment().endOf('month')],
                   'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            });

            // Khi người dùng chọn xong ngày và nhấn "Áp dụng"
            $('#date-range-filter').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            });

            // Khi người dùng nhấn "Hủy" hoặc xóa ngày
            $('#date-range-filter').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
        });
    </script>

    {{-- Script cho DataTable (giữ nguyên) --}}
    <script>
        $(document).ready(function () {
            // Tắt các tính năng mặc định của DataTable vì chúng ta đã tự làm bộ lọc
            $('#data-table-customers').DataTable({
                "responsive": true,
                "lengthChange": true, // Bật lại để người dùng có thể chọn số dòng
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                "language": {"url": "/admin/plugins/datatables/Vietnamese.json"},
                "order": [[5, "desc"]], // Sắp xếp theo ngày đăng ký
                "searching": true // Bật lại ô tìm kiếm mặc định của DataTable
            }).buttons().container().appendTo('#data-table-customers_wrapper .col-md-6:eq(0)');
        });
    </script>
@endpush