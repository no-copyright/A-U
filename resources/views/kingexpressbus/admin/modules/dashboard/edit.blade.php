@extends('kingexpressbus.admin.layouts.main')
@section('title', 'Quản lý Trang chủ')

@section('content')
    <form action="{{ route('admin.dashboard.update') }}" method="POST">
        @csrf

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-ban"></i> Có lỗi xảy ra! Vui lòng kiểm tra lại các trường.</h5>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @include('kingexpressbus.admin.partials.alerts')
        
        <div class="card card-primary">
            <div class="card-header"><h3 class="card-title">Khu vực Banner chính</h3></div>
            <div class="card-body">
                <x-inputs.text label="Tiêu đề Banner" name="banners[title]" :value="old('banners.title', $homePage->banners->title ?? '')"/>
                <x-inputs.text-area label="Mô tả Banner" name="banners[description]" :value="old('banners.description', $homePage->banners->description ?? '')"/>
                <x-inputs.image-link-array label="Danh sách ảnh Banner" name="banners[images]" :value="old('banners.images', $homePage->banners->images ?? [])"/>
            </div>
        </div>

        <div class="card card-info">
            <div class="card-header"><h3 class="card-title">Khu vực Thống kê (Stats)</h3></div>
            <div class="card-body">
                <div id="stats-container">
                    @foreach (old('stats', $homePage->stats ?? []) as $index => $stat)
                        <div class="stat-item">
                            <h5>Mục thống kê #<span class="stat-item-index">{{ $loop->iteration }}</span></h5>
                            <x-inputs.number label="Giá trị (Số)" name="stats[{{ $index }}][value]" :value="$stat['value'] ?? ''" />
                            <x-inputs.text label="Mô tả" name="stats[{{ $index }}][description]" :value="$stat['description'] ?? ''" />
                            <div class="form-group">
                                <label>Ảnh minh họa</label>
                                <div class="input-group">
                                    <input readonly type="text" class="form-control ckfinder-input" name="stats[{{ $index }}][images]" value="{{ $stat['images'] ?? '' }}">
                                    <span class="input-group-append">
                                        <button type="button" class="btn btn-secondary ckfinder-popup-dynamic">Duyệt Ảnh</button>
                                    </span>
                                </div>
                                <div class="mt-2">
                                    <img src="{{ $stat['images'] ?? '' }}" alt="Preview" style="max-width: 150px;" @style(['display: none;' => empty($stat['images'])])>
                                </div>
                            </div>
                            <div class="mt-auto text-center pt-3">
                                <button type="button" class="btn btn-danger btn-sm remove-item">Xóa mục này</button>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" id="add-stat-item" class="btn btn-secondary">Thêm mục Thống kê</button>
            </div>
        </div>

        <div class="card card-warning">
            <div class="card-header"><h3 class="card-title">Câu hỏi thường gặp (FAQs)</h3></div>
            <div class="card-body">
                <div id="fags-container">
                     @foreach (old('fags', $homePage->fags ?? []) as $index => $fag)
                        <div class="fag-item">
                            <h5>Câu hỏi #<span class="fag-item-index">{{ $loop->iteration }}</span></h5>
                            <x-inputs.text label="Câu hỏi" name="fags[{{ $index }}][question]" :value="$fag['question'] ?? ''"/>
                            <x-inputs.text-area label="Câu trả lời" name="fags[{{ $index }}][answer]" :value="$fag['answer'] ?? ''"/>
                            <div class="mt-auto text-center pt-3">
                                <button type="button" class="btn btn-danger btn-sm remove-item">Xóa câu hỏi này</button>
                            </div>
                        </div>
                     @endforeach
                </div>
                 <button type="button" id="add-fag-item" class="btn btn-secondary">Thêm câu hỏi</button>
            </div>
        </div>

        <div class="card card-success">
            <div class="card-header"><h3 class="card-title">Thư viện Ảnh</h3></div>
            <div class="card-body">
                <x-inputs.image-link-array label="Danh sách ảnh trưng bày" name="images" :value="old('images', $homePage->images ?? [])"/>
            </div>
        </div>

         <div class="card card-danger">
            <div class="card-header"><h3 class="card-title">Link YouTube</h3></div>
            <div class="card-body">
                <x-inputs.text-array label="Danh sách link YouTube" name="link_youtubes" :value="old('link_youtubes', $homePage->link_youtubes ?? [])"/>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        </div>
    </form>

    <div id="templates" style="display: none;">
        <div id="stat-item-template">
            <div class="stat-item">
                <h5>Mục thống kê #<span class="stat-item-index"></span></h5>
                <div class="form-group">
                    <label>Giá trị (Số)</label>
                    <input type="number" class="form-control" name="stats[__INDEX__][value]" placeholder="Enter Giá trị (Số)" required>
                </div>
                <div class="form-group">
                    <label>Mô tả</label>
                    <input type="text" class="form-control" name="stats[__INDEX__][description]" placeholder="Enter Mô tả" required>
                </div>
                <div class="form-group">
                    <label>Ảnh minh họa</label>
                    <div class="input-group">
                        <input readonly type="text" class="form-control ckfinder-input" name="stats[__INDEX__][images]" value="">
                        <span class="input-group-append">
                            <button type="button" class="btn btn-secondary ckfinder-popup-dynamic">Duyệt Ảnh</button>
                        </span>
                    </div>
                    <div class="mt-2">
                        <img src="" alt="Preview" style="max-width: 150px; display: none;">
                    </div>
                </div>
                <div class="mt-auto text-center pt-3">
                    <button type="button" class="btn btn-danger btn-sm remove-item">Xóa mục này</button>
                </div>
            </div>
        </div>

        <div id="fag-item-template">
            <div class="fag-item">
                <h5>Câu hỏi #<span class="fag-item-index"></span></h5>
                <div class="form-group">
                    <label>Câu hỏi</label>
                    <input type="text" class="form-control" name="fags[__INDEX__][question]" placeholder="Enter Câu hỏi" required>
                </div>
                <div class="form-group">
                    <label>Câu trả lời</label>
                    <textarea class="form-control" name="fags[__INDEX__][answer]" required rows="3" placeholder="Enter Câu trả lời"></textarea>
                </div>
                <div class="mt-auto text-center pt-3">
                    <button type="button" class="btn btn-danger btn-sm remove-item">Xóa câu hỏi này</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {

    function setupDynamicAdder(addButtonId, containerId, templateId, itemClass) {
        const addButton = document.getElementById(addButtonId);
        const container = document.getElementById(containerId);
        
        addButton.addEventListener('click', function() {
            const newIndex = container.querySelectorAll(`.${itemClass}`).length;
            const template = document.getElementById(templateId).innerHTML;
            const newItemHtml = template.replace(/__INDEX__/g, newIndex);
            
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = newItemHtml;
            const newItem = tempDiv.firstElementChild;

            newItem.querySelector(`.${itemClass}-index`).textContent = newIndex + 1;
            container.appendChild(newItem);

            const ckfinderButton = newItem.querySelector('.ckfinder-popup-dynamic');
            if (ckfinderButton) {
                setupCkFinderForButton(ckfinderButton);
            }
        });
    }

    function setupDynamicRemover(containerId, itemClass) {
        const container = document.getElementById(containerId);
        container.addEventListener('click', function(e) {
            if (e.target && e.target.matches('.remove-item')) {
                e.target.closest(`.${itemClass}`).remove();
                reindexItems(container, itemClass);
            }
        });
    }

    function reindexItems(container, itemClass) {
        const items = container.querySelectorAll(`.${itemClass}`);
        items.forEach((item, index) => {
            item.querySelector(`.${itemClass}-index`).textContent = index + 1;
            
            item.querySelectorAll('input, textarea').forEach(input => {
                const name = input.getAttribute('name');
                if (name) {
                    input.setAttribute('name', name.replace(/\[\d+\]/, `[${index}]`));
                }
            });
        });
    }

    function setupCkFinderForButton(button) {
        button.addEventListener('click', function() {
            CKFinder.popup({
                chooseFiles: true,
                onInit: function(finder) {
                    finder.on('files:choose', function(evt) {
                        const file = evt.data.files.first();
                        const path = new URL(file.getUrl()).pathname;
                        
                        const inputGroup = button.closest('.input-group');
                        const input = inputGroup.querySelector('.ckfinder-input');
                        const previewImg = inputGroup.closest('.form-group').querySelector('img');

                        if(input) input.value = path;
                        if (previewImg) {
                            previewImg.src = path;
                            previewImg.style.display = 'block';
                        }
                    });
                }
            });
        });
    }

    setupDynamicAdder('add-stat-item', 'stats-container', 'stat-item-template', 'stat-item');
    setupDynamicRemover('stats-container', 'stat-item');

    setupDynamicAdder('add-fag-item', 'fags-container', 'fag-item-template', 'fag-item');
    setupDynamicRemover('fags-container', 'fag-item');

    document.querySelectorAll('.ckfinder-popup-dynamic').forEach(setupCkFinderForButton);
});
</script>
@endpush