<?php

namespace App\Http\Traits;

use Illuminate\Support\Str;

/**
 * Trait này cung cấp một phương thức chung để tạo slug duy nhất bằng cách nối ID vào cuối.
 * Cấu trúc slug sẽ là: `ten-bai-viet-123`
 */
trait SlugGenerator
{
    /**
     * Tạo một slug duy nhất từ chuỗi nguồn và ID.
     *
     * @param string $sourceString Chuỗi để tạo slug (ví dụ: title, name).
     * @param int|string $id ID của bản ghi.
     * @return string Slug đã được tạo.
     */
    protected function generateSlug(string $sourceString, $id): string
    {
        $baseSlug = Str::slug($sourceString);
        return $baseSlug . '-' . $id;
    }
}