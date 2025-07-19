@php
    $isEdit = !empty($review?->id);
@endphp

@extends('kingexpressbus.admin.layouts.main')
@section('title', $isEdit ? 'Sửa Đánh giá' : 'Tạo Đánh giá')

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $isEdit ? 'Sửa Đánh giá: ' . $review->name : 'Tạo mới Đánh giá' }}</h3>
        </div>
        <form
            action="{{ $isEdit ? route('admin.parents-corner.update', ['parents_corner' => $review->id]) : route('admin.parents-corner.store') }}"
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
                    <div class="col-md-6">
                        <x-inputs.text label="Tên Phụ huynh" name="name" :value="old('name', $review?->name)" required/>
                    </div>
                    <div class="col-md-6">
                        <x-inputs.text label="Mô tả" name="describe" :value="old('describe', $review?->describe)" required/>
                    </div>
                </div>
                <x-inputs.image-link label="Ảnh đại diện" name="image" :value="old('image', $review?->image)" required/>
                <x-inputs.text label="Đánh giá" name="rate" :value="old('rate', $review?->rate)" required/>
                <x-inputs.editor label="Nội dung chi tiết" name="content" :value="old('content', $review?->content)" required/>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Cập nhật' : 'Tạo mới' }}</button>
                <a href="{{ route('admin.parents-corner.index') }}" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
@endsection