@php
$isEdit = !empty($teacher?->id);
@endphp

@extends('kingexpressbus.admin.layouts.main')
@section('title', $isEdit ? 'Sửa thông tin Giáo viên' : 'Thêm mới Giáo viên')

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $isEdit ? 'Sửa thông tin: ' . $teacher->full_name : 'Thêm mới Giáo viên' }}</h3>
    </div>
    <form
        action="{{ $isEdit ? route('admin.teachers.update', ['teacher' => $teacher->id]) : route('admin.teachers.store') }}"
        method="post">
        @csrf
        @if($isEdit)
        @method('PUT')
        @endif

        <div class="card-body">
            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Có lỗi xảy ra!</strong> Vui lòng kiểm tra lại các trường dữ liệu.
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            @endif

            <div class="row">
                <div class="col-md-5">
                    <x-inputs.text label="Họ và tên" name="full_name" :value="old('full_name', $teacher?->full_name)" required />
                </div>
                <div class="col-md-4">
                    <x-inputs.text label="Quốc tịch" name="role" :value="old('role', $teacher?->role)" required />
                </div>
                <div class="col-md-3">
                    <x-inputs.number label="Độ ưu tiên" name="priority" :value="old('priority', $teacher?->priority ?? 99)" required />
                </div>
            </div>

            <x-inputs.image-link label="Ảnh đại diện (Avatar)" name="avatar" :value="old('avatar', $teacher?->avatar)" required />

            <div class="row">
                <div class="col-md-6">
                    <x-inputs.email label="Email" name="email" :value="old('email', $teacher?->email)" required />
                </div>
                <div class="col-md-6">
                    <x-inputs.text label="Link Facebook" name="facebook" :value="old('facebook', $teacher?->facebook)" required />
                </div>
            </div>

            <x-inputs.text-area label="Bằng cấp, Chứng chỉ (ngăn cách nhau bởi dấu ',' ví dụ: toeic, ielts)" name="qualifications" :value="old('qualifications', $teacher?->qualifications)" required />

            <x-inputs.editor label="Mô tả thêm về giáo viên (Tiểu sử, kinh nghiệm,...)" name="description" :value="old('description', $teacher?->description)" />

        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Cập nhật' : 'Thêm mới' }}</button>
            <a href="{{ route('admin.teachers.index') }}" class="btn btn-secondary">Hủy</a>
        </div>
    </form>
</div>
@endsection