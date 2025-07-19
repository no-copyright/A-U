@extends('kingexpressbus.admin.layouts.main')
@section('title', 'Quản lý Liên hệ')

@section('content')
    <form action="{{ route('admin.contact.update') }}" method="POST">
        @csrf

        @include('kingexpressbus.admin.partials.alerts')

        <div class="card card-primary">
            <div class="card-header"><h3 class="card-title">Thông tin chung</h3></div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <x-inputs.text label="Số điện thoại" name="phone" :value="old('phone', $contactInfo->phone)" />
                <x-inputs.email label="Email" name="email" :value="old('email', $contactInfo->email)" />
                <x-inputs.text label="Link Facebook" name="facebook" :value="old('facebook', $contactInfo->facebook)" />
            </div>
        </div>

        <div class="card card-info">
            <div class="card-header"><h3 class="card-title">Danh sách Địa chỉ (Chi nhánh)</h3></div>
            <div class="card-body">
                <div id="address-container" style="display: flex; flex-direction: column; gap: 20px;">
                    @foreach (old('address', $contactInfo->address ?? []) as $index => $address)
                        <div class="address-item">
                            <h5>Địa chỉ #<span class="address-item-index">{{ $loop->iteration }}</span></h5>
                            <x-inputs.text label="Địa chỉ" name="address[{{ $index }}][address]" :value="$address['address'] ?? ''" />
                            <x-inputs.text label="Link Google Map" name="address[{{ $index }}][googlemap]" :value="$address['googlemap'] ?? ''" />
                            <div class="mt-2 text-center">
                                <button type="button" class="btn btn-danger btn-sm remove-item">Xóa địa chỉ này</button>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" id="add-address-item" class="btn btn-secondary mt-3">Thêm Địa chỉ</button>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        </div>
    </form>

    {{-- Template for new address items --}}
    <div id="templates" style="display: none;">
        <div id="address-item-template">
            <div class="address-item">
                <h5>Địa chỉ #<span class="address-item-index"></span></h5>
                <div class="form-group">
                    <label>Địa chỉ</label>
                    <input type="text" class="form-control" name="address[__INDEX__][address]" placeholder="Enter Địa chỉ" required>
                </div>
                <div class="form-group">
                    <label>Link Google Map</label>
                    <input type="text" class="form-control" name="address[__INDEX__][googlemap]" placeholder="Enter Link Google Map" required>
                </div>
                <div class="mt-2 text-center">
                    <button type="button" class="btn btn-danger btn-sm remove-item">Xóa địa chỉ này</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .address-item {
        border: 1px solid #dee2e6 !important;
        border-radius: .25rem !important;
        padding: 1.25rem !important;
        display: flex;
        flex-direction: column;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    const addButton = document.getElementById('add-address-item');
    const container = document.getElementById('address-container');
    const template = document.getElementById('address-item-template').innerHTML;

    addButton.addEventListener('click', function() {
        const newIndex = container.querySelectorAll('.address-item').length;
        let newItemHtml = template.replace(/__INDEX__/g, newIndex);
        
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = newItemHtml;
        const newItem = tempDiv.firstElementChild;
        newItem.querySelector('.address-item-index').textContent = newIndex + 1;
        container.appendChild(newItem);
    });

    container.addEventListener('click', function(e) {
        if (e.target && e.target.matches('.remove-item')) {
            e.target.closest('.address-item').remove();
            reindexAddressItems();
        }
    });

    function reindexAddressItems() {
        const items = container.querySelectorAll('.address-item');
        items.forEach((item, index) => {
            item.querySelector('.address-item-index').textContent = index + 1;
            item.querySelectorAll('input').forEach(input => {
                const name = input.getAttribute('name');
                if (name) {
                    input.setAttribute('name', name.replace(/\[\d+\]/, `[${index}]`));
                }
            });
        });
    }
});
</script>
@endpush