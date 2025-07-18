<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{$label}}</h3>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label>Thêm ảnh mới</label>
            <div class="input-group">
                <input readonly type="text" class="form-control" placeholder="Chọn ảnh từ trình duyệt...">
                <span class="input-group-append">
                    {{-- Sửa lỗi: Đổi ID thành class để tránh trùng lặp. Truyền $name vào để định danh. --}}
                    <button type="button" class="btn btn-secondary ckfinder-popup-for-array" data-name="{{ $name }}">
                        Duyệt Ảnh
                    </button>
                </span>
            </div>
        </div>

        <div class="mt-2 image-preview-grid">
            {{-- Dùng một id duy nhất cho list --}}
            <ul class="list-unstyled d-flex flex-wrap" id="list-{{ $name }}">
                @if (is_array($value) && count($value) > 0)
                    @foreach ($value as $item)
                        <li class="grid-item">
                            <img src="{{ $item }}" alt="Image Preview">
                            <input type="hidden" name="{{ $name }}[]" value="{{ $item }}">
                            <button type="button" class="btn btn-danger btn-sm btn-remove-image">
                                <i class="fas fa-times"></i>
                            </button>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>

        @error($name)
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Script này chỉ chạy một lần và xử lý tất cả các component có trên trang --}}
@pushonce('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Dùng event delegation cho tất cả các nút 'Duyệt Ảnh' loại array
            document.body.addEventListener('click', function(e) {
                if (e.target && e.target.matches('.ckfinder-popup-for-array')) {
                    const button = e.target;
                    const name = button.dataset.name;
                    const list = document.getElementById(`list-${name}`);

                    if (!list) return;

                    CKFinder.popup({
                        chooseFiles: true,
                        multiple: true, // Cho phép chọn nhiều file
                        onInit: function (finder) {
                            finder.on('files:choose', function (evt) {
                                const files = evt.data.files;
                                files.forEach(function(file) {
                                    const path = new URL(file.getUrl()).pathname;
                                    const li = document.createElement('li');
                                    li.className = 'grid-item';
                                    li.innerHTML = `
                                        <img src="${path}" alt="Image Preview">
                                        <input type="hidden" name="${name}[]" value="${path}">
                                        <button type="button" class="btn btn-danger btn-sm btn-remove-image">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    `;
                                    list.appendChild(li);
                                });
                            });
                        }
                    });
                }
            });

            // Dùng event delegation cho tất cả các nút xóa ảnh
            document.body.addEventListener('click', function(e) {
                const removeButton = e.target.closest('.btn-remove-image');
                if (removeButton && removeButton.closest('.grid-item')) {
                    removeButton.closest('.grid-item').remove();
                }
            });
        });
    </script>
@endpushonce