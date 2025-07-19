<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use App\Http\Traits\SlugGenerator; // Import trait để tạo slug

/**
 * File seeder này chứa toàn bộ dữ liệu mẫu cho dự án KingExpressBus.
 * Nó được gọi từ file DatabaseSeeder.php chính.
 */
class KingExpressBusSeeder extends Seeder
{
    use SlugGenerator; // Sử dụng trait để tạo slug nhất quán với logic trong Controller

    /**
     * Chạy seed cho cơ sở dữ liệu.
     */
    public function run(): void
    {
        // Xóa dữ liệu cũ theo thứ tự ngược lại để tránh lỗi khóa ngoại
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('news')->truncate();
        DB::table('customers')->truncate();
        DB::table('categories')->truncate();
        DB::table('trainings')->truncate();
        DB::table('teachers')->truncate();
        DB::table('parents_corner')->truncate();
        DB::table('document')->truncate();
        DB::table('home_page')->truncate();
        DB::table('contact')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Gọi các hàm để seed dữ liệu cho từng bảng
        // Thứ tự gọi rất quan trọng để đảm bảo khóa ngoại tồn tại
        $this->seedCategories();
        $this->seedTrainings();
        $this->seedNews();       // Phụ thuộc vào Categories
        $this->seedCustomers();  // Phụ thuộc vào Trainings
        $this->seedTeachers();
        $this->seedParentsCorner();
        $this->seedDocuments();
        $this->seedHomePage();
        $this->seedContact();
    }
    
    //======================================================================
    // HÀM SEED DỮ LIỆU CHO CÁC BẢNG
    //======================================================================

    private function seedCategories()
    {
        $faker = Faker::create('vi_VN');
        $categories = [];
        $categoryNames = [
            'Hoạt động ngoại khóa', 'Sự kiện nổi bật', 'Góc chia sẻ kinh nghiệm',
            'Thông báo từ trung tâm', 'Lịch khai giảng', 'Ưu đãi học phí',
            'Phương pháp học tập hiệu quả', 'Cảm nhận học viên', 'Câu chuyện thành công'
        ];

        foreach ($categoryNames as $name) {
            $categories[] = [
                'name'       => $name,
                'slug'       => Str::slug($name), // Slug ban đầu, sẽ được cập nhật sau
                'count'      => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('categories')->insert($categories);

        // Cập nhật lại slug với ID để đảm bảo duy nhất, giống logic trong Controller
        $allCategories = DB::table('categories')->get();
        foreach ($allCategories as $category) {
            $finalSlug = $this->generateSlug($category->name, $category->id);
            DB::table('categories')->where('id', $category->id)->update(['slug' => $finalSlug]);
        }
    }

    private function seedNews()
    {
        $faker = Faker::create('vi_VN');
        $categoryIds = DB::table('categories')->pluck('id');
        $newsItems = [];

        $sampleThumbnails = [
            'userfiles/images/R5AT3838.jpg', 'userfiles/images/R5AT3841.jpg', 'userfiles/images/R5AT3848.jpg',
            'userfiles/images/R5AT3853.jpg', 'userfiles/images/R5AT3856.jpg', 'userfiles/images/R5AT3860.jpg',
            'userfiles/images/R5AT3865.jpg', 'userfiles/images/R5AT3870.jpg', 'userfiles/images/R5AT3872.jpg',
            'userfiles/images/R5AT3875.jpg',
        ];

        for ($i = 0; $i < 20; $i++) {
            $title = $faker->unique()->sentence(6);
            $categoryId = $faker->randomElement($categoryIds);

            $newsItems[] = [
                'title'       => $title,
                'slug'        => Str::slug($title),
                'thumbnail'   => $faker->randomElement($sampleThumbnails),
                'author'      => 'Admin',
                'view'        => $faker->numberBetween(50, 2000),
                'category_id' => $categoryId,
                'content'     => '<h2>' . $faker->sentence(4) . '</h2><p>' . $faker->paragraphs(3, true) . '</p><blockquote>' . $faker->sentence(10) . '</blockquote><p>' . $faker->paragraphs(4, true) . '</p>',
                'created_at'  => now()->subDays($i),
                'updated_at'  => now()->subDays($i),
            ];
            DB::table('categories')->where('id', $categoryId)->increment('count');
        }
        DB::table('news')->insert($newsItems);

        $allNews = DB::table('news')->get();
        foreach ($allNews as $news) {
            $finalSlug = $this->generateSlug($news->title, $news->id);
            DB::table('news')->where('id', $news->id)->update(['slug' => $finalSlug]);
        }
    }

    private function seedTrainings()
    {
        $faker = Faker::create('vi_VN');
        $trainings = [];
        
        $sampleThumbnails = [
            'userfiles/images/R5AT3879.jpg', 'userfiles/images/R5AT3881.jpg', 'userfiles/images/R5AT3884.jpg',
            'userfiles/images/R5AT3893.jpg', 'userfiles/images/R5AT3894.jpg', 'userfiles/images/R5AT3898.jpg',
        ];

        for ($i = 0; $i < 15; $i++) {
            $title = $faker->unique()->sentence(4);
            $curriculum = [];
            for ($j = 1; $j <= 5; $j++) {
                $curriculum[] = ['module'  => "Module {$j}: " . $faker->sentence(3), 'content' => $faker->paragraph(4)];
            }

            $trainings[] = [
                'priority'    => $i + 1,
                'slug'        => Str::slug($title),
                'title'       => $title,
                'age'         => $faker->randomElement(['4-6 tuổi', '7-10 tuổi', '11-15 tuổi', 'Trên 15 tuổi']),
                'description' => '<p>' . $faker->paragraphs(2, true) . '</p>',
                'thumbnail'   => $faker->randomElement($sampleThumbnails),
                'duration'    => $faker->randomElement(['3 tháng', '6 tháng', '12 tháng']),
                'outcome'     => '<ul><li>' . implode('</li><li>', $faker->sentences(3)) . '</li></ul>',
                'method'      => $faker->randomElement(['Học trực tuyến', 'Học tại trung tâm', 'Học kèm 1-1']),
                'speaking'    => '<p>' . $faker->paragraph(2) . '</p>',
                'listening'   => '<p>' . $faker->paragraph(2) . '</p>',
                'reading'     => '<p>' . $faker->paragraph(2) . '</p>',
                'writing'     => '<p>' . $faker->paragraph(2) . '</p>',
                'curriculum'  => json_encode($curriculum),
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }
        DB::table('trainings')->insert($trainings);
        
        $allTrainings = DB::table('trainings')->get();
        foreach ($allTrainings as $training) {
            $finalSlug = $this->generateSlug($training->title, $training->id);
            DB::table('trainings')->where('id', $training->id)->update(['slug' => $finalSlug]);
        }
    }

    private function seedCustomers()
    {
        $faker = Faker::create('vi_VN');
        $trainingIds = DB::table('trainings')->pluck('id');
        $customers = [];
        for ($i = 0; $i < 25; $i++) {
            $customers[] = [
                'training_id'        => $faker->optional(0.8)->randomElement($trainingIds),
                'full_name_parent'   => $faker->name,
                'phone'              => '0' . $faker->numberBetween(900000000, 999999999),
                'email'              => $faker->unique()->safeEmail,
                'full_name_children' => $faker->firstName . ' ' . $faker->lastName,
                'status'             => $faker->randomElement(['pending', 'confirmed', 'cancelled']),
                'date_of_birth'      => $faker->dateTimeBetween('-15 years', '-4 years')->format('Y-m-d'),
                'address'            => $faker->address,
                'note'               => $faker->optional(0.5)->paragraph,
                'created_at'         => now()->subDays($i),
                'updated_at'         => now()->subDays($i),
            ];
        }
        DB::table('customers')->insert($customers);
    }
    
    private function seedTeachers()
    {
        $faker = Faker::create('vi_VN');
        $teachers = [];
        $sampleAvatars = [
            'userfiles/images/R5AT4140.jpg', 'userfiles/images/R5AT4145.jpg', 'userfiles/images/R5AT4153.jpg',
            'userfiles/images/R5AT4155.jpg', 'userfiles/images/R5AT4157.jpg', 'userfiles/images/R5AT4159.jpg',
            'userfiles/images/R5AT4162.jpg', 'userfiles/images/R5AT4163.jpg',
        ];

        for ($i = 0; $i < 10; $i++) {
            $fullName = $faker->unique()->name;
            $teachers[] = [
                'priority'       => $i + 1,
                'full_name'      => $fullName,
                'role'           => $faker->randomElement(['Việt Nam', 'Bản xứ (Anh)', 'Bản xứ (Mỹ)']),
                'qualifications' => 'TESOL, IELTS 8.5, ' . $faker->sentence(2),
                'avatar'         => $faker->randomElement($sampleAvatars),
                'slug'           => Str::slug($fullName),
                'facebook'       => 'https://facebook.com/' . Str::slug($fullName),
                'email'          => $faker->unique()->safeEmail,
                'description'    => '<h3>Kinh nghiệm giảng dạy</h3><p>' . $faker->paragraphs(3, true) . '</p>',
                'created_at'     => now(),
                'updated_at'     => now(),
            ];
        }
        DB::table('teachers')->insert($teachers);
        
        $allTeachers = DB::table('teachers')->get();
        foreach ($allTeachers as $teacher) {
            $finalSlug = $this->generateSlug($teacher->full_name, $teacher->id);
            DB::table('teachers')->where('id', $teacher->id)->update(['slug' => $finalSlug]);
        }
    }

    private function seedParentsCorner()
    {
        $faker = Faker::create('vi_VN');
        $reviews = [];
        $sampleImages = [
            'userfiles/images/R5AT4198.jpg', 'userfiles/images/R5AT4200.jpg', 'userfiles/images/R5AT4202.jpg',
            'userfiles/images/R5AT4205.jpg', 'userfiles/images/R5AT4207.jpg', 'userfiles/images/R5AT4208.jpg',
        ];
        
        for ($i = 0; $i < 12; $i++) {
            $name = 'Phụ huynh ' . $faker->name;
            $reviews[] = [
                'priority'   => $i + 1,
                'slug'       => Str::slug($name),
                'image'      => $faker->randomElement($sampleImages),
                'rate'       => str_repeat('⭐', $faker->numberBetween(4, 5)),
                'name'       => $name,
                'describe'   => 'Phụ huynh bé ' . $faker->firstName,
                'content'    => '<blockquote>' . $faker->paragraph(2) . '</blockquote><p>' . $faker->paragraph(3) . '</p>',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('parents_corner')->insert($reviews);

        $allReviews = DB::table('parents_corner')->get();
        foreach ($allReviews as $review) {
            $finalSlug = $this->generateSlug($review->name, $review->id);
            DB::table('parents_corner')->where('id', $review->id)->update(['slug' => $finalSlug]);
        }
    }

    private function seedDocuments()
    {
        $faker = Faker::create('vi_VN');
        $docs = [];
        $sampleFiles = [
            'userfiles/files/tai-lieu-1.pdf', 'userfiles/files/brochure.docx',
            'userfiles/files/thoi-khoa-bieu.xlsx', 'userfiles/files/presentation.pptx',
        ];

        for ($i = 0; $i < 10; $i++) {
            $docs[] = [
                'priority'   => $i + 1,
                'name'       => 'Tài liệu ' . $faker->sentence(3),
                'src'        => $faker->randomElement($sampleFiles),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('document')->insert($docs);
    }

    private function seedHomePage()
    {
        DB::table('home_page')->insert([
            'id' => 1,
            'banners' => json_encode([
                'title'       => 'Khơi dậy tiềm năng, vững bước tương lai cùng A&U',
                'description' => 'Môi trường học tập chuẩn quốc tế, giúp con tự tin giao tiếp và chinh phục các kỳ thi.',
                'images'      => ['userfiles/images/R5AT4211.jpg', 'userfiles/images/R5AT4212.jpg', 'userfiles/images/R5AT4215.jpg'],
            ]),
            'stats' => json_encode([
                ['value' => 10, 'description' => 'Năm kinh nghiệm', 'images' => 'userfiles/images/R5AT4219.jpg'],
                ['value' => 50, 'description' => 'Giáo viên ưu tú', 'images' => 'userfiles/images/R5AT4222.jpg'],
                ['value' => 2000, 'description' => 'Học viên theo học', 'images' => 'userfiles/images/R5AT4226.jpg'],
                ['value' => 95, 'description' => '% Phụ huynh hài lòng', 'images' => 'userfiles/images/R5AT4230.jpg'],
            ]),
            'fags' => json_encode([
                ['question' => 'Trung tâm có lớp học thử miễn phí không?', 'answer' => 'Có, chúng tôi có các buổi học thử định kỳ. Vui lòng để lại thông tin để được tư vấn lịch học gần nhất.'],
                ['question' => 'Lộ trình học cho bé được xây dựng như thế nào?', 'answer' => 'Mỗi học viên sẽ được kiểm tra đầu vào và tư vấn lộ trình cá nhân hóa để đảm bảo hiệu quả học tập tốt nhất.'],
                ['question' => 'Đội ngũ giáo viên của trung tâm có trình độ như thế nào?', 'answer' => '100% giáo viên tại A&U có bằng cấp sư phạm, chứng chỉ TESOL/IELTS và nhiều năm kinh nghiệm giảng dạy.'],
            ]),
            'images' => json_encode([
                'userfiles/images/R5AT4240.jpg', 'userfiles/images/R5AT4246.jpg', 'userfiles/images/R5AT4255.jpg',
                'userfiles/images/R5AT4259.jpg', 'userfiles/images/R5AT4262.jpg', 'userfiles/images/R5AT4264.jpg',
                'userfiles/images/R5AT4267.jpg', 'userfiles/images/R5AT4270.jpg',
            ]),
            'link_youtubes' => json_encode(['https://www.youtube.com/watch?v=example1', 'https://www.youtube.com/watch?v=example2']),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
    
    private function seedContact()
    {
        DB::table('contact')->insert([
            'id' => 1,
            'address' => json_encode([
                ['address' => '123 Đường ABC, Phường X, Quận Y, TP. Hồ Chí Minh', 'googlemap' => 'https://maps.google.com/'],
                ['address' => '456 Đường DEF, Phường A, Quận B, TP. Hà Nội', 'googlemap' => 'https://maps.google.com/'],
            ]),
            'phone' => '0987654321',
            'email' => 'contact@kingexpressbus.com',
            'facebook' => 'https://facebook.com/kingexpressbus',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}