<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Traits\SlugGenerator;

class KnowledgeCategorySeeder extends Seeder
{
    use SlugGenerator;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $categoryName = 'Kiến thức và kinh nghiệm';

        // Kiểm tra xem category đã tồn tại chưa để tránh tạo trùng lặp
        $existingCategory = DB::table('categories')->where('name', $categoryName)->first();

        if (!$existingCategory) {
            // Dữ liệu ban đầu
            $data = [
                'name'       => $categoryName,
                'slug'       => Str::slug($categoryName),
                'count'      => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Thêm vào DB để lấy ID
            $id = DB::table('categories')->insertGetId($data);

            // Tạo slug cuối cùng với ID và cập nhật lại
            $finalSlug = $this->generateSlug($categoryName, $id);
            DB::table('categories')->where('id', $id)->update(['slug' => $finalSlug]);

            $this->command->info("Category '{$categoryName}' created successfully.");
        } else {
            $this->command->info("Category '{$categoryName}' already exists. Skipping.");
        }
    }
}
