@extends('kingexpressbus.admin.layouts.main')
@section('title', 'Chi tiết Đăng ký của: ' . $customer->full_name_parent)

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Chi tiết Đăng ký #{{ $customer->id }}</h3>
        <div class="card-tools">
            <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Quay lại Danh sách
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            {{-- Thông tin Phụ huynh --}}
            <div class="col-md-6 mb-4">
                <h5>Thông tin Phụ huynh</h5>
                <table class="table table-sm table-bordered">
                    <tr>
                        <th style="width: 150px;">Họ tên</th>
                        <td>{{ $customer->full_name_parent }}</td>
                    </tr>
                    <tr>
                        <th>Điện thoại</th>
                        <td>{{ $customer->phone }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $customer->email }}</td>
                    </tr>
                </table>
            </div>

            {{-- Thông tin Học viên --}}
            <div class="col-md-6 mb-4">
                <h5>Thông tin Học viên</h5>
                <table class="table table-sm table-bordered">
                    <tr>
                        <th style="width: 150px;">Họ tên</th>
                        <td>{{ $customer->full_name_children }}</td>
                    </tr>
                    <tr>
                        <th>Ngày sinh</th>
                        <td>{{ \Carbon\Carbon::parse($customer->date_of_birth)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Địa chỉ</th>
                        <td>{{ $customer->address }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row">
            {{-- Thông tin Đăng ký --}}
            <div class="col-12">
                <h5>Thông tin Đăng ký</h5>
                <table class="table table-sm table-bordered">
                     <tr>
                        <th style="width: 150px;">Khóa học đăng ký</th>
                        <td><strong>{{ $customer->training_title ?? 'N/A' }}</strong></td>
                    </tr>
                    <tr>
                        <th>Ngày đăng ký</th>
                        <td>{{ \Carbon\Carbon::parse($customer->created_at)->format('d/m/Y H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>Ghi chú</th>
                        {{-- Dùng nl2br để hiển thị xuống dòng cho đẹp --}}
                        <td>{!! nl2br(e($customer->note)) ?: 'Không có ghi chú.' !!}</td>
                    </tr>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection