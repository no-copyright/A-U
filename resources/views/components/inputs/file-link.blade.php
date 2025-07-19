<div class="form-group">
    <label for="input-{{$name}}">{{$label}}</label>
    <div class="input-group">
        {{-- Input vẫn cần name và id để submit form và cho label --}}
        <input readonly type="text" class="form-control" name="{{ $name }}" id="input-{{ $name }}"
            required value="{{$value ?: old($name)}}">
        <span class="input-group-append">
            {{-- Thay id bằng class để script có thể xử lý nhiều nút--}}
            <button type="button" class="btn btn-secondary ckfinder-file-popup-button">
                Duyệt File
            </button>
        </span>
    </div>
    @error($name)
    <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

{{--
Sử dụng @pushonce để đảm bảo đoạn script này chỉ được thêm vào trang MỘT LẦN,
dù bạn có dùng component này nhiều lần trên cùng một trang.
--}}
@pushonce("scripts")
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Lắng nghe sự kiện click trên toàn bộ body
        document.body.addEventListener('click', function(e) {
            // Kiểm tra xem phần tử được click có phải là nút duyệt file của chúng ta không
            if (e.target && e.target.matches('.ckfinder-file-popup-button')) {
                const button = e.target;

                CKFinder.popup({
                    chooseFiles: true,
                    resourceType: 'Files', // Chỉ định mở thư mục Files
                    width: 800,
                    height: 600,
                    onInit: function(finder) {
                        finder.on('files:choose', function(evt) {
                            const file = evt.data.files.first();
                            const path = new URL(file.getUrl()).pathname;

                            // Tìm input tương ứng bằng cách đi ngược lại cây DOM
                            const inputGroup = button.closest('.input-group');
                            if (inputGroup) {
                                const input = inputGroup.querySelector('.form-control');
                                if (input) {
                                    input.value = path;
                                }
                            }
                        });
                    }
                });
            }
        });
    });
</script>
@endpushonce