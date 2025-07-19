@php
$isEdit = !empty($training?->id);
@endphp

@extends('kingexpressbus.admin.layouts.main')
@section('title', $isEdit ? 'Sửa Khoá Đào tạo' : 'Tạo Khoá Đào tạo')

@push('styles')
<style>
    .curriculum-item {
        border: 1px solid #dee2e6 !important;
        border-radius: .25rem !important;
        padding: 1.25rem !important;
        margin-bottom: 1rem !important;
        background-color: #f8f9fa;
    }
</style>
@endpush

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

            {{-- CÁC TRƯỜNG INPUT KHÁC --}}
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

            {{-- PHẦN GIÁO TRÌNH (CURRICULUM) ĐÃ ĐƯỢC ĐƠN GIẢN HÓA --}}
            <div class="card card-info mt-4">
                <div class="card-header"><h3 class="card-title">Giáo trình (Curriculum)</h3></div>
                <div class="card-body">
                    <div id="curriculum-container">
                        @php
                            $curriculumData = old('curriculum', is_string($training?->curriculum) ? json_decode($training->curriculum, true) : $training?->curriculum) ?? [];
                        @endphp
                        @foreach ($curriculumData as $index => $item)
                            <div class="curriculum-item">
                                <h5>Module #<span class="curriculum-item-index">{{ $loop->iteration }}</span></h5>
                                <div class="form-group">
                                    <label>Tên Module</label>
                                    <input type="text" class="form-control" name="curriculum[{{ $index }}][module]" value="{{ $item['module'] ?? '' }}" placeholder="VD: Module 1: Welcome to my world!" required>
                                </div>
                                <div class="form-group">
                                    <label>Nội dung Module</label>
                                    {{-- Đã thay thế CKEditor bằng textarea thường --}}
                                    <textarea name="curriculum[{{ $index }}][content]" class="form-control" rows="3" required>{{ $item['content'] ?? '' }}</textarea>
                                </div>
                                <div class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm remove-curriculum-item">Xóa Module</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" id="add-curriculum-item" class="btn btn-secondary">Thêm Module</button>
                </div>
            </div>

        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Cập nhật' : 'Tạo mới' }}</button>
            <a href="{{ route('admin.training.index') }}" class="btn btn-secondary">Hủy</a>
        </div>
    </form>
</div>

{{-- Template ẩn cho các module mới --}}
<div id="templates" style="display: none;">
    <div id="curriculum-item-template">
        <div class="curriculum-item">
            <h5>Module #<span class="curriculum-item-index"></span></h5>
            <div class="form-group">
                <label>Tên Module</label>
                <input type="text" class="form-control" name="curriculum[__INDEX__][module]" placeholder="VD: Module 1: Welcome to my world!" required>
            </div>
            <div class="form-group">
                <label>Nội dung Module</label>
                 {{-- Đã thay thế CKEditor bằng textarea thường --}}
                <textarea name="curriculum[__INDEX__][content]" class="form-control" rows="3" required></textarea>
            </div>
            <div class="text-center">
                <button type="button" class="btn btn-danger btn-sm remove-curriculum-item">Xóa Module</button>
            </div>
        </div>
    </div>
</div>
@endsection


@push('scripts')
{{-- Script đã được rút gọn, không còn logic của CKEditor --}}
<script>
document.addEventListener("DOMContentLoaded", function() {
    const container = document.getElementById("curriculum-container");
    const addButton = document.getElementById("add-curriculum-item");
    const template = document.getElementById("curriculum-item-template").innerHTML;

    // Hàm sắp xếp lại index của các item sau khi xóa
    function reindexItems() {
        container.querySelectorAll('.curriculum-item').forEach((item, index) => {
            // Cập nhật số thứ tự hiển thị
            item.querySelector('.curriculum-item-index').textContent = index + 1;
            
            // Cập nhật thuộc tính 'name' của input và textarea
            item.querySelectorAll('input, textarea').forEach(input => {
                const name = input.getAttribute('name');
                if (name) {
                    input.setAttribute('name', name.replace(/\[\d+\]/, `[${index}]`));
                }
            });
        });
    }

    // Sự kiện click nút "Thêm Module"
    addButton.addEventListener('click', function() {
        const newIndex = container.querySelectorAll('.curriculum-item').length;
        // Thay thế placeholder __INDEX__ bằng chỉ số mới
        let newItemHtml = template.replace(/__INDEX__/g, newIndex);
        
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = newItemHtml;
        
        const newItem = tempDiv.firstElementChild;
        newItem.querySelector('.curriculum-item-index').textContent = newIndex + 1;
        container.appendChild(newItem);
    });

    // Sự kiện click nút "Xóa" (sử dụng event delegation)
    container.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-curriculum-item')) {
            // Xóa phần tử cha gần nhất có class .curriculum-item
            e.target.closest('.curriculum-item').remove();
            // Sắp xếp lại index
            reindexItems();
        }
    });
});
</script>
@endpush