@php
$isEdit = !empty($training?->id);
@endphp

@extends('kingexpressbus.admin.layouts.main')
@section('title', $isEdit ? 'Sửa Khoá Đào tạo' : 'Tạo Khoá Đào tạo')

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $isEdit ? 'Sửa Khoá Đào tạo: ' . $training->title : 'Tạo mới Khoá Đào tạo' }}</h3>
    </div>
    <form
        action="{{ $isEdit ? route('admin.training.update', ['training' => $training->id]) : route('admin.training.store') }}"
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
                <div class="col-md-9">
                    <x-inputs.text label="Tiêu đề Khoá học" name="title" :value="old('title', $training?->title)" required />
                </div>
                <div class="col-md-3">
                    <x-inputs.number label="Độ ưu tiên (Số nhỏ ưu tiên cao)" name="priority" :value="old('priority', $training?->priority ?? 99)" required />
                </div>
            </div>
            
            <x-inputs.image-link label="Ảnh đại diện (Thumbnail)" name="thumbnail" :value="old('thumbnail', $training?->thumbnail)" required />
            <x-inputs.editor label="Mô tả chung" name="description" :value="old('description', $training?->description)" required />

            <div class="row">
                <div class="col-md-4">
                    <x-inputs.text label="Độ tuổi" name="age" :value="old('age', $training?->age)" required />
                </div>
                <div class="col-md-4">
                    <x-inputs.text label="Thời lượng" name="duration" :value="old('duration', $training?->duration)" required />
                </div>
                <div class="col-md-4">
                    <x-inputs.text label="Phương pháp" name="method" :value="old('method', $training?->method)" required />
                </div>
            </div>

            <x-inputs.text-area label="Kết quả đầu ra" name="outcome" :value="old('outcome', $training?->outcome)" required rows="4" />

            <h5 class="mt-4 mb-3 text-primary border-bottom pb-2">Chi tiết chương trình học</h5>
            <x-inputs.text-area label="Kỹ năng Nói (Speaking)" name="speaking" :value="old('speaking', $training?->speaking)" required />
            <x-inputs.text-area label="Kỹ năng Nghe (Listening)" name="listening" :value="old('listening', $training?->listening)" required />
            <x-inputs.text-area label="Kỹ năng Đọc (Reading)" name="reading" :value="old('reading', $training?->reading)" required />
            <x-inputs.text-area label="Kỹ năng Viết (Writing)" name="writing" :value="old('writing', $training?->writing)" required />

            <x-inputs.editor label="Giáo trình (Curriculum)" name="curriculum" :value="old('curriculum', $training?->curriculum)" />

        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Cập nhật' : 'Tạo mới' }}</button>
            <a href="{{ route('admin.training.index') }}" class="btn btn-secondary">Hủy</a>
        </div>
    </form>
</div>
@endsection