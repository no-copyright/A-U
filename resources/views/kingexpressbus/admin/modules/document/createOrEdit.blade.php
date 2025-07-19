@php
$isEdit = !empty($document?->id);
@endphp

@extends('kingexpressbus.admin.layouts.main')
@section('title', $isEdit ? 'Sửa Tài liệu' : 'Tạo Tài liệu')

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $isEdit ? 'Sửa Tài liệu: ' . $document->name : 'Tạo mới Tài liệu' }}</h3>
    </div>
    <form
        action="{{ $isEdit ? route('admin.documents.update', ['document' => $document->id]) : route('admin.documents.store') }}"
        method="post">
        @csrf
        @if($isEdit)
        @method('PUT')
        @endif

        <div class="card-body">
            @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Có lỗi xảy ra!</strong> Vui lòng kiểm tra lại các trường dữ liệu.
            </div>
            @endif
            <div class="row">
                <div class="col-md-9">
                    <x-inputs.text label="Tên Tài liệu" name="name" :value="old('name', $document?->name)" required />
                </div>
                <div class="col-md-3">
                    <x-inputs.number label="Độ ưu tiên" name="priority" :value="old('priority', $document?->priority ?? 99)" required />
                </div>
            </div>

            <x-inputs.file-link label="Chọn File (Đường dẫn)" name="src" :value="old('src', $document?->src)" required />
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Cập nhật' : 'Tạo mới' }}</button>
            <a href="{{ route('admin.documents.index') }}" class="btn btn-secondary">Hủy</a>
        </div>
    </form>
</div>
@endsection