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
                <x-inputs.text label="Tên Tài liệu" name="name" :value="old('name', $document?->name)" required/>
                
                {{-- THAY ĐỔI Ở ĐÂY: Sử dụng component mới 'file-link' --}}
                <x-inputs.file-link label="Chọn File (Đường dẫn)" name="src" :value="old('src', $document?->src)" required/>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Cập nhật' : 'Tạo mới' }}</button>
                <a href="{{ route('admin.documents.index') }}" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
@endsection