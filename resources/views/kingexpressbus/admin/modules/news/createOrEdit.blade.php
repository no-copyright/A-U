@php
    $isEdit = !empty($news?->id);
@endphp

@extends('kingexpressbus.admin.layouts.main')
@section('title', $isEdit ? 'Sửa Tin tức' : 'Tạo Tin tức')

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $isEdit ? 'Sửa Tin tức: ' . $news->title : 'Tạo mới Tin tức' }}</h3>
        </div>
        <form
            action="{{ $isEdit ? route('admin.news.update', ['news' => $news->id]) : route('admin.news.store') }}"
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

                <x-inputs.text label="Tiêu đề" name="title" :value="old('title', $news?->title)" required/>
                
                {{-- Cột 'slug' sẽ được tạo tự động từ Tiêu đề trong Controller. --}}

                <x-inputs.image-link label="Ảnh đại diện (Thumbnail)" name="thumbnail" :value="old('thumbnail', $news?->thumbnail)" required/>
                
                <div class="row">
                    <div class="col-md-6">
                        <x-inputs.text label="Tác giả" name="author" :value="old('author', $news?->author)" required/>
                    </div>
                    <div class="col-md-6">
                        {{-- Giả sử biến $categories được truyền từ Controller --}}
                        <x-inputs.select label="Danh mục" name="category_id" required>
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id', $news?->category_id) == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </x-inputs.select>
                    </div>
                </div>

                <x-inputs.editor label="Nội dung" name="content" :value="old('content', $news?->content)" required/>

            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Cập nhật' : 'Tạo mới' }}</button>
                <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
@endsection