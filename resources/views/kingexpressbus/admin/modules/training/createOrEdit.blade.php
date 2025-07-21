@php
$isEdit = !empty($training?->id);
$curriculumItems = old('curriculum', $training?->curriculum ?? []);
@endphp

@extends('kingexpressbus.admin.layouts.main')
@section('title', $isEdit ? 'Sửa Khoá Đào tạo' : 'Tạo Khoá Đào tạo')

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $isEdit ? 'Sửa Khoá Đào tạo: ' . $training->title : 'Tạo mới Khoá Đào tạo' }}</h3>
    </div>
    <form action="{{ $isEdit ? route('admin.training.update', ['training' => $training->id]) : route('admin.training.store') }}" method="post">
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

            {{-- THÔNG TIN CƠ BẢN --}}
            <div class="row">
                <div class="col-md-9">
                    <x-inputs.text label="Tiêu đề khóa học" name="title" :value="old('title', $training?->title)" required />
                </div>
                <div class="col-md-3">
                    <x-inputs.number label="Độ ưu tiên" name="priority" :value="old('priority', $training?->priority ?? 99)" required />
                </div>
            </div>

            <x-inputs.image-link label="Ảnh đại diện (Thumbnail)" name="thumbnail" :value="old('thumbnail', $training?->thumbnail)" required />

            <div class="row">
                <div class="col-md-6">
                    <x-inputs.text label="Độ tuổi phù hợp" name="age" :value="old('age', $training?->age)" required />
                </div>
                <div class="col-md-6">
                    <x-inputs.text label="Thời lượng khóa học" name="duration" :value="old('duration', $training?->duration)" required />
                </div>
            </div>

            <x-inputs.text-area label="Mô tả ngắn" name="description" :value="old('description', $training?->description)" required />
            <x-inputs.text-area label="Kết quả đầu ra" name="outcome" :value="old('outcome', $training?->outcome)" required />
            <x-inputs.text label="Phương pháp giảng dạy" name="method" :value="old('method', $training?->method)" required />

            <hr>
            {{-- KỸ NĂNG --}}
            <h4>Kỹ năng</h4>
            <div class="row">
                <div class="col-md-6">
                    <x-inputs.text-area label="Speaking" name="speaking" :value="old('speaking', $training?->speaking)" required />
                    <x-inputs.text-area label="Listening" name="listening" :value="old('listening', $training?->listening)" required />
                </div>
                <div class="col-md-6">
                    <x-inputs.text-area label="Reading" name="reading" :value="old('reading', $training?->reading)" required />
                    <x-inputs.text-area label="Writing" name="writing" :value="old('writing', $training?->writing)" required />
                </div>
            </div>

            <hr>
            {{-- NỘI DUNG VÀ HÌNH ẢNH CHI TIẾT --}}
            <h4>Nội dung và hình ảnh chi tiết</h4>
            <x-inputs.editor label="Nội dung chi tiết khóa học" name="content" :value="old('content', $training?->content)" />
            <x-inputs.image-link-array label="Thư viện ảnh khóa học" name="images" :value="old('images', $training?->images ?? [])" />

            <hr>
            {{-- CHƯƠNG TRÌNH HỌC (CẤU TRÚC TÙY CHỈNH) --}}
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">Chương trình học</h3>
                </div>
                <div class="card-body">
                    <div id="curriculum-container">
                        @if(!empty($curriculumItems))
                        @foreach($curriculumItems as $index => $item)
                        <div class="curriculum-item">
                            <h5 class="mb-3">Học phần #<span class="curriculum-item-index">{{ $loop->iteration }}</span></h5>
                            <div class="form-group">
                                <label>Tên học phần (Module)</label>
                                <input type="text" class="form-control" name="curriculum[{{ $index }}][module]" value="{{ $item['module'] ?? '' }}" required>
                            </div>
                            <div class="form-group">
                                <label>Nội dung học phần</label>
                                <textarea class="form-control curriculum-editor" name="curriculum[{{ $index }}][content]" id="curriculum-editor-{{ $index }}">{!! $item['content'] ?? '' !!}</textarea>
                            </div>
                            <div class="text-right">
                                <button type="button" class="btn btn-danger btn-sm remove-curriculum-item">Xóa học phần</button>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                    <button type="button" id="add-curriculum-item" class="btn btn-secondary mt-3">Thêm học phần</button>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Cập nhật' : 'Tạo mới' }}</button>
            <a href="{{ route('admin.training.index') }}" class="btn btn-secondary">Hủy</a>
        </div>
    </form>
</div>

{{-- TEMPLATE CHO JAVASCRIPT --}}
<div id="templates" style="display: none;">
    <div id="curriculum-item-template">
        <div class="curriculum-item">
            <h5 class="mb-3">Học phần #<span class="curriculum-item-index"></span></h5>
            <div class="form-group">
                <label>Tên học phần (Module)</label>
                <input type="text" class="form-control" name="curriculum[__INDEX__][module]" required>
            </div>
            <div class="form-group">
                <label>Nội dung học phần</label>
                <textarea class="form-control curriculum-editor" name="curriculum[__INDEX__][content]" id="curriculum-editor-__INDEX__"></textarea>
            </div>
            <div class="text-right">
                <button type="button" class="btn btn-danger btn-sm remove-curriculum-item">Xóa học phần</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .curriculum-item {
        border: 1px solid #dee2e6;
        border-radius: .25rem;
        padding: 1.25rem;
        margin-bottom: 1rem;
        background-color: #f8f9fa;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const container = document.getElementById("curriculum-container");
        const addButton = document.getElementById("add-curriculum-item");
        const template = document.getElementById("curriculum-item-template").innerHTML;
        let ckeditorInstances = {};

        function initCkEditor(editorId) {
            if (ckeditorInstances[editorId] || !document.getElementById(editorId)) {
                return;
            }
            CKEDITOR.ClassicEditor.create(document.getElementById(editorId), {
                toolbar: {
                    items: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'undo', 'redo'],
                    shouldNotGroupWhenFull: true
                },
                placeholder: 'Nội dung chi tiết cho học phần...',
                removePlugins: ['CKFinder', 'EasyImage', 'Image', 'ImageCaption', 'ImageStyle', 'ImageToolbar', 'ImageUpload', 'MediaEmbed']
            }).then(editor => {
                ckeditorInstances[editorId] = editor;
            }).catch(error => {
                console.error(`Error creating CKEditor for ID ${editorId}:`, error);
            });
        }

        function reindexItems() {
            container.querySelectorAll('.curriculum-item').forEach((item, index) => {
                item.querySelector('.curriculum-item-index').textContent = index + 1;
                item.querySelectorAll('input, textarea').forEach(input => {
                    const name = input.getAttribute('name');
                    if (name) {
                        input.setAttribute('name', name.replace(/\[\d+\]|\[__INDEX__\]/, `[${index}]`));
                    }
                    if (input.matches('.curriculum-editor')) {
                        const newId = `curriculum-editor-${index}`;
                        const oldId = input.getAttribute('id');
                        if (oldId && ckeditorInstances[oldId] && oldId !== newId) {
                            ckeditorInstances[newId] = ckeditorInstances[oldId];
                            delete ckeditorInstances[oldId];
                        }
                        input.setAttribute('id', newId);
                    }
                });
            });
        }

        addButton.addEventListener('click', function() {
            const newIndex = container.querySelectorAll('.curriculum-item').length;
            let newItemHtml = template.replace(/__INDEX__/g, newIndex);

            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = newItemHtml;
            const newItem = tempDiv.firstElementChild;
            newItem.querySelector('.curriculum-item-index').textContent = newIndex + 1;

            container.appendChild(newItem);
            initCkEditor(`curriculum-editor-${newIndex}`);
        });

        container.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-curriculum-item')) {
                const itemToRemove = e.target.closest('.curriculum-item');
                const editorTextarea = itemToRemove.querySelector('.curriculum-editor');
                if (editorTextarea) {
                    const editorId = editorTextarea.id;
                    if (ckeditorInstances[editorId]) {
                        ckeditorInstances[editorId].destroy();
                        delete ckeditorInstances[editorId];
                    }
                }
                itemToRemove.remove();
                reindexItems();
            }
        });

        // Initialize CKEditor for existing items on page load
        document.querySelectorAll('.curriculum-editor').forEach(editorEl => {
            initCkEditor(editorEl.id);
        });
    });
</script>
@endpush