@php
    $isEdit = !empty($category?->id);
@endphp

@extends('kingexpressbus.admin.layouts.main')
@section('title', $isEdit ? 'Sửa Danh mục' : 'Tạo Danh mục')

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $isEdit ? 'Sửa Danh mục: ' . $category->name : 'Tạo mới Danh mục' }}</h3>
        </div>
        <form
            action="{{ $isEdit ? route('admin.categories.update', ['category' => $category->id]) : route('admin.categories.store') }}"
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

                <x-inputs.text label="Tên Danh mục" name="name" :value="old('name', $category?->name)" required/>
                
                {{-- Cột 'count' sẽ được cập nhật tự động, không cần input ở đây. --}}

            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Cập nhật' : 'Tạo mới' }}</button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
@endsection