@extends('kingexpressbus.admin.layouts.main')
@section('title', 'Danh sách Khách hàng đăng ký')

@push('styles')
<style>
    /* Kiểu cho ô select hiển thị chính */
    .status-dropdown {
        font-weight: bold;
        font-size: 0.9rem;
        padding-top: .25rem;
        padding-bottom: .25rem;
        line-height: 1.5;
        vertical-align: middle;
    }
    /* Đảm bảo các tùy chọn bên trong luôn có nền trắng, chữ đen để dễ đọc */
    .status-dropdown option {
        background-color: #fff !important;
        color: #000 !important;
    }
</style>
@endpush

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Bộ lọc và Tìm kiếm</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.customers.index') }}" method="GET">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="search-input">Tìm kiếm (Tên, SĐT):</label>
                        <input type="text" name="search" id="search-input" class="form-control form-control-sm" value="{{ request('search') }}" placeholder="Nhập tên phụ huynh, học viên, SĐT...">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="status-filter">Trạng thái:</label>
                        <select name="status" id="status-filter" class="form-control form-control-sm">
                            <option value="">-- Tất cả --</option>
                            <option value="pending" @selected(request('status') == 'pending')>Chờ xử lý</option>
                            <option value="confirmed" @selected(request('status') == 'confirmed')>Đã xác nhận</option>
                            <option value="cancelled" @selected(request('status') == 'cancelled')>Đã hủy</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="date-range-filter">Ngày đăng ký:</label>
                        <input type="text" name="date_range" id="date-range-filter" class="form-control form-control-sm" value="{{ request('date_range') }}" placeholder="Chọn khoảng ngày">
                    </div>
                    <div class="form-group col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-sm mr-2"><i class="fas fa-filter"></i> Lọc</button>
                        <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-times"></i> Xóa</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Quản lý Khách hàng đăng ký</h3>
        </div>
        <div class="card-body">
            @include('kingexpressbus.admin.partials.alerts')

            <div class="table-responsive">
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
                    @forelse($customers as $customer)
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
                                    <select name="status"
                                            class="form-control form-control-sm text-white status-dropdown {{ $statusConfig[$customer->status]['class'] ?? 'bg-secondary' }}"
                                            onchange="this.form.submit()">
                                        @foreach ($statusConfig as $key => $value)
                                            {{-- ĐÃ LOẠI BỎ CLASS MÀU NỀN KHỎI THẺ OPTION --}}
                                            <option value="{{ $key }}" @selected($customer->status == $key)>
                                                {{ $value['text'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td>
                                <a class="btn btn-info btn-sm" href="{{ route('admin.customers.show', ['customer' => $customer->id]) }}" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('admin.customers.destroy', ['customer' => $customer->id]) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa đăng ký này?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Không tìm thấy dữ liệu phù hợp.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3 d-flex justify-content-end">
                {{ $customers->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('#date-range-filter').daterangepicker({
                autoUpdateInput: false,
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

            $('#date-range-filter').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            });

            $('#date-range-filter').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $('#data-table-customers').DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "paging": false,
                "searching": false,
                "ordering": false,
                "info": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#data-table-customers_wrapper .col-md-6:eq(0)');
        });
    </script>
@endpush