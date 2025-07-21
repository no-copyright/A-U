<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use App\Http\Traits\SlugGenerator;

class KingExpressBusSeeder extends Seeder
{
    use SlugGenerator;

    public function run(): void
    {
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

        $this->seedCategories();
        $this->seedTrainings();
        $this->seedNews();
        $this->seedTeachers();
        $this->seedParentsCorner();
        $this->seedHomePage();
    }

    private function seedCategories()
    {
        $categoryName = 'Kiến thức và kinh nghiệm';
        DB::table('categories')->insert([
            'name'       => $categoryName,
            'slug'       => Str::slug($categoryName),
            'count'      => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $category = DB::table('categories')->first();
        if ($category) {
            $finalSlug = $this->generateSlug($category->name, $category->id);
            DB::table('categories')->where('id', $category->id)->update(['slug' => $finalSlug]);
        }
    }

    private function seedNews()
    {
        $faker = Faker::create('vi_VN');
        $sampleThumbnails = ['/userfiles/images/R5AT3838.jpg', '/userfiles/images/R5AT3841.jpg', '/userfiles/images/R5AT3848.jpg'];
        $knowledgeCategory = DB::table('categories')->first();
        if (!$knowledgeCategory) return;

        $sampleNewsContent = [
            'Một trong những phương pháp hiệu quả nhất để giúp trẻ học tiếng Anh tại nhà là tạo ra một môi trường ngôn ngữ tự nhiên. Phụ huynh có thể dán nhãn các đồ vật trong nhà bằng tiếng Anh, cùng con xem các chương trình hoạt hình hoặc nghe nhạc thiếu nhi bằng tiếng Anh. Việc tiếp xúc thường xuyên sẽ giúp con thẩm thấu ngôn ngữ một cách vô thức.',
            'Đừng biến việc học thành áp lực. Hãy lồng ghép tiếng Anh vào các trò chơi mà trẻ yêu thích như trốn tìm (đếm số bằng tiếng Anh), board game (dạy về màu sắc, con vật), hoặc các hoạt động nghệ thuật. Khi trẻ cảm thấy vui vẻ, khả năng tiếp thu và ghi nhớ sẽ tăng lên đáng kể.',
            'Đọc sách truyện song ngữ hoặc truyện tranh tiếng Anh là một cách tuyệt vời để mở rộng vốn từ vựng và làm quen với cấu trúc câu. Hãy bắt đầu với những cuốn sách có hình ảnh minh họa đẹp mắt và nội dung đơn giản, phù hợp với lứa tuổi của con. Cùng con đọc và giải thích những từ mới sẽ giúp tăng cường sự gắn kết gia đình.',
            'Khen ngợi và động viên là liều thuốc tinh thần vô giá. Thay vì chỉ trích lỗi sai, hãy tập trung vào những nỗ lực và tiến bộ của con, dù là nhỏ nhất. Sự công nhận từ cha mẹ sẽ giúp con xây dựng sự tự tin và không sợ mắc lỗi khi học một ngôn ngữ mới.',
            'Tận dụng các ứng dụng học tiếng Anh dành cho trẻ em cũng là một lựa chọn thông minh. Nhiều ứng dụng được thiết kế với giao diện thân thiện, bài học sinh động qua video và trò chơi, giúp duy trì hứng thú học tập cho trẻ trong thời gian dài.'
        ];

        $newsItems = [];
        for ($i = 0; $i < 5; $i++) {
            $title = "Bí quyết giúp con học tiếng Anh tại nhà - Mẹo số " . ($i + 1);
            $excerpt = $faker->randomElement($sampleNewsContent);
            $content = '<h2>Khám phá phương pháp học hiệu quả cho trẻ</h2><p>' . implode('</p><p>', $faker->randomElements($sampleNewsContent, 3)) . '</p><blockquote>Việc học ngoại ngữ sớm không chỉ giúp trẻ phát triển trí não mà còn mở ra nhiều cơ hội trong tương lai.</blockquote>';

            $newsItems[] = [
                'title' => $title,
                'slug' => Str::slug($title),
                'excerpt' => $excerpt,
                'thumbnail' => $faker->randomElement($sampleThumbnails),
                'author' => 'AU English',
                'view' => $faker->numberBetween(100, 3000),
                'category_id' => $knowledgeCategory->id,
                'content' => $content,
                'created_at' => now()->subDays($i),
                'updated_at' => now()->subDays($i),
            ];
        }

        DB::table('news')->insert($newsItems);
        DB::table('categories')->where('id', $knowledgeCategory->id)->update(['count' => count($newsItems)]);
        $allNews = DB::table('news')->get();
        foreach ($allNews as $news) {
            $finalSlug = $this->generateSlug($news->title, $news->id);
            DB::table('news')->where('id', $news->id)->update(['slug' => $finalSlug]);
        }
    }

    private function seedTrainings()
    {
        $faker = Faker::create('vi_VN');
        $sampleThumbnails = [
            '/userfiles/images/R5AT3879.jpg', '/userfiles/images/R5AT3881.jpg', '/userfiles/images/R5AT3884.jpg',
            '/userfiles/images/R5AT3893.jpg', '/userfiles/images/R5AT3894.jpg', '/userfiles/images/R5AT3898.jpg',
        ];

        $coursesData = [
            [
                'priority' => 1, 'title' => 'Tiếng Anh Mẫu giáo (3 - 6 tuổi)', 'age' => '3 - 6 tuổi',
                'description' => 'Giai đoạn vàng để con bắt đầu học ngôn ngữ mới. Chương trình giúp con tiếp cận tiếng Anh một cách tự nhiên, vui vẻ và hiệu quả, tạo nền tảng vững chắc cho tương lai.',
                'content' => '<h3>Điểm nổi bật của chương trình</h3><ol><li><strong>Tập trung nghe – nói:</strong> Giúp con phản xạ nhanh và phát âm chuẩn bản xứ.</li><li><strong>Học vui vẻ, hiệu quả:</strong> Học qua hình ảnh, trò chơi, bài hát và hoạt động tương tác.</li><li><strong>Tiếp cận tự nhiên:</strong> Làm quen với 44 âm trong tiếng Anh qua phương pháp ngữ âm hiện đại.</li></ol>',
                'outcome' => 'Phát âm đúng theo phương pháp ngữ âm quốc tế | Nhận biết và đánh vần lưu loát | Giao tiếp tự tin ngay từ những năm đầu học',
                'speaking' => 'Luyện phát âm chuẩn theo bảng ngữ âm quốc tế (phonics), tập phản xạ giao tiếp qua các bài hát và trò chơi.',
                'listening' => 'Nghe và nhận biết các âm, từ vựng quen thuộc thông qua các câu chuyện, bài hát và khẩu lệnh của giáo viên bản xứ.',
                'reading' => 'Làm quen với mặt chữ, nhận biết các từ đơn giản qua thẻ từ (flashcards) và các câu chuyện hình ảnh sinh động.',
                'writing' => 'Tập tô chữ, sao chép các chữ cái và từ vựng đơn giản, bước đầu hình thành kỹ năng cầm bút và nhận diện chữ viết.',
                'curriculum_content' => 'Học phần 1: Hello World! Bé sẽ làm quen với các câu chào hỏi đơn giản như "Hello", "Goodbye", học từ vựng về màu sắc và các con vật gần gũi thông qua bài hát "Old MacDonald" và các trò chơi vận động vui nhộn.'
            ],
            [
                'priority' => 2, 'title' => 'Tiếng Anh Tiểu học (6 - 11 tuổi)', 'age' => '6 - 11 tuổi',
                'description' => 'Tiếng Anh không chỉ là điểm số, mà là kỹ năng sống. Chương trình cung cấp một lộ trình rõ ràng, bài bản, giúp con tự tin giao tiếp và đạt kết quả cao trong học tập.',
                'content' => '<h3>ĐẶC ĐIỂM CỦA KHOÁ HỌC</h3><ol><li><strong>100% HỌC VỚI GIÁO VIÊN NƯỚC NGOÀI:</strong> Tập trung phát triển kỹ năng giao tiếp thực tế.</li><li><strong>CHƯƠNG TRÌNH HỌC TẬP TÍCH HỢP:</strong> Bám sát khung Cambridge và hỗ trợ chương trình tại trường.</li><li><strong>LỘ TRÌNH HOÁ CÁ NHÂN:</strong> Điều chỉnh theo khả năng của con và có báo cáo định kỳ cho phụ huynh.</li></ol>',
                'outcome' => 'Tự tin giao tiếp với giáo viên bản xứ | Nắm vững ngữ pháp và từ vựng theo chuẩn Cambridge | Cải thiện điểm số trên lớp',
                'speaking' => 'Thực hành nói về các chủ đề quen thuộc như gia đình, trường học, sở thích. Học cách diễn đạt suy nghĩ mạch lạc và tự nhiên.',
                'listening' => 'Luyện nghe hiểu các đoạn hội thoại, câu chuyện dài hơn và nắm bắt ý chính, chi tiết quan trọng trong bài.',
                'reading' => 'Phát triển kỹ năng đọc hiểu văn bản, truyện ngắn, và trả lời các câu hỏi liên quan đến nội dung đã đọc để củng cố từ vựng.',
                'writing' => 'Học cách viết câu hoàn chỉnh, các đoạn văn ngắn mô tả về bản thân, gia đình và các sự vật, hiện tượng xung quanh.',
                'curriculum_content' => 'Học phần 1: My Family and Friends. Học viên học cách giới thiệu về các thành viên trong gia đình, bạn bè. Thực hành đặt câu hỏi và trả lời về tuổi tác, nghề nghiệp, sở thích bằng các cấu trúc câu đơn giản và thông dụng.'
            ],
            [
                'priority' => 3, 'title' => 'Tiếng Anh THCS (11 - 13 tuổi)', 'age' => '11 - 13 tuổi',
                'description' => 'Lộ trình tối ưu giúp học sinh xây dựng nền tảng tiếng Anh học thuật vững chắc, sẵn sàng chinh phục các kỳ thi quan trọng như IELTS ở bậc THPT.',
                'content' => '<h3>Các đặc điểm nổi bật:</h3><ul><li>🔹 <strong>Xây nền tảng học thuật vững chắc:</strong> Củng cố sâu từ vựng – ngữ pháp – phát âm.</li><li>🔹 <strong>Phát triển toàn diện 4 kỹ năng.</strong></li><li>🔹 <strong>Lồng ghép chiến lược làm bài IELTS từ sớm.</strong></li></ul>',
                'outcome' => 'Nền tảng Ngữ pháp - Từ vựng học thuật vững chắc | Thành thạo 4 kỹ năng Nghe - Nói - Đọc - Viết | Đạt trình độ tương đương B1-B2 Cambridge',
                'speaking' => 'Rèn luyện kỹ năng tranh biện, thuyết trình về các chủ đề xã hội và học thuật, phát triển tư duy phản biện bằng tiếng Anh.',
                'listening' => 'Luyện nghe các bài giảng, tin tức và hội thoại phức tạp, tập kỹ năng ghi chú (note-taking) và tóm tắt thông tin nghe được.',
                'reading' => 'Đọc hiểu các bài báo, văn bản học thuật, phân tích và suy luận để tìm ra ý chính, thông tin ẩn và quan điểm của tác giả.',
                'writing' => 'Thực hành viết các đoạn văn nghị luận, email trang trọng và các bài luận ngắn theo cấu trúc chuẩn (mở bài, thân bài, kết luận).',
                'curriculum_content' => 'Học phần 1: Academic Skills Focus. Rèn luyện kỹ năng đọc lướt (skimming) và đọc quét (scanning) qua các bài đọc về chủ đề môi trường. Học cách viết một đoạn văn nêu quan điểm với cấu trúc 3 phần rõ ràng.'
            ],
        ];

        $trainings = [];
        foreach ($coursesData as $course) {
            $trainings[] = [
                'priority' => $course['priority'], 'slug' => Str::slug($course['title']), 'title' => $course['title'],
                'age' => $course['age'], 'description' => $course['description'],
                'thumbnail' => $faker->randomElement($sampleThumbnails),
                'duration' => $faker->randomElement(['3 tháng', '6 tháng', 'Theo khóa']),
                'outcome' => $course['outcome'], 'method' => $faker->randomElement(['Học tại trung tâm', 'Học trực tuyến']),
                'speaking' => $course['speaking'], 'listening' => $course['listening'],
                'reading' => $course['reading'], 'writing' => $course['writing'],
                'content' => $course['content'], 'images' => json_encode($faker->randomElements($sampleThumbnails, 3)),
                'curriculum' => json_encode([['module'  => "Nội dung học phần mẫu", 'content' => $course['curriculum_content']]]),
                'created_at' => now(), 'updated_at' => now(),
            ];
        }
        DB::table('trainings')->insert($trainings);
        $allTrainings = DB::table('trainings')->get();
        foreach ($allTrainings as $training) {
            $finalSlug = $this->generateSlug($training->title, $training->id);
            DB::table('trainings')->where('id', $training->id)->update(['slug' => $finalSlug]);
        }
    }

    private function seedTeachers()
    {
        $faker = Faker::create('vi_VN');
        $sampleAvatars = ['/userfiles/images/R5AT4140.jpg', '/userfiles/images/R5AT4145.jpg', '/userfiles/images/R5AT4153.jpg'];
        $teachers = [];
        for ($i = 0; $i < 3; $i++) {
            $fullName = $faker->name;
            $teachers[] = [
                'priority' => $i + 1, 'full_name' => $fullName,
                'role' => $faker->randomElement(['Giáo viên Việt Nam', 'Giáo viên Bản xứ']),
                'qualifications' => 'Chứng chỉ TESOL, IELTS 8.0+, ' . $faker->sentence(3, true),
                'avatar' => $sampleAvatars[$i], 'slug' => Str::slug($fullName),
                'facebook' => 'https://facebook.com/auenglish', 'email' => $faker->unique()->safeEmail,
                'description' => '<h3>Kinh nghiệm giảng dạy</h3><p>Với hơn 5 năm kinh nghiệm giảng dạy, thầy/cô đã giúp đỡ hàng trăm học viên cải thiện trình độ tiếng Anh và đạt được mục tiêu học tập. Phương pháp giảng dạy tập trung vào sự tương tác và truyền cảm hứng cho học viên.</p>',
                'created_at' => now(), 'updated_at' => now(),
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
        $reviewsData = [
            [
                'rate' => 'Con tôi tự tin và phát âm chuẩn hơn hẳn!',
                'content' => 'Sau một khóa học tại AU, bé nhà mình đã mạnh dạn hơn rất nhiều. Trước đây con rất nhát, không dám nói tiếng Anh, nhưng giờ con có thể tự tin giới thiệu bản thân và hát các bài hát tiếng Anh. Các thầy cô rất nhiệt tình và kiên nhẫn, phương pháp học qua trò chơi thực sự hiệu quả.',
                'image' => '/userfiles/images/R5AT4198.jpg'
            ],
            [
                'rate' => 'Chương trình học bài bản, con tiến bộ rõ rệt.',
                'content' => 'Tôi rất hài lòng với lộ trình học tập tại trung tâm. Con không chỉ được học với giáo viên bản xứ mà còn được củng cố ngữ pháp thường xuyên. Điểm số trên lớp của con đã cải thiện đáng kể, và quan trọng nhất là con tìm thấy niềm yêu thích với môn tiếng Anh.',
                'image' => '/userfiles/images/R5AT4200.jpg'
            ],
            [
                'rate' => 'Trung tâm chuyên nghiệp, giáo viên tận tâm.',
                'content' => 'Điều tôi ấn tượng nhất là sự chuyên nghiệp và tận tâm của đội ngũ AU. Từ giáo viên đến các bạn trợ giảng đều rất quan tâm đến từng học viên. Trung tâm thường xuyên cập nhật tình hình học tập của con, giúp tôi nắm bắt được sự tiến bộ và phối hợp cùng nhà trường để hỗ trợ con tốt nhất.',
                'image' => '/userfiles/images/R5AT4202.jpg'
            ],
        ];

        $reviews = [];
        foreach ($reviewsData as $i => $data) {
            $name = 'Phụ huynh ' . $faker->name;
            $reviews[] = [
                'priority' => $i + 1, 'slug' => Str::slug($name . '-' . $i),
                'image' => $data['image'], 'rate' => $data['rate'], 'name' => $name,
                'describe' => 'Phụ huynh bé ' . $faker->firstName, 'content' => $data['content'],
                'created_at' => now(), 'updated_at' => now(),
            ];
        }
        DB::table('parents_corner')->insert($reviews);
    }

    private function seedHomePage()
    {
        DB::table('home_page')->insert([
            'id' => 1,
            'banners' => json_encode([
                'title' => 'Khơi dậy tiềm năng, vững bước tương lai cùng AU English',
                'description' => 'Môi trường học tập chuẩn quốc tế, giúp con tự tin giao tiếp và chinh phục các kỳ thi.',
                'images' => ['/userfiles/images/R5AT4211.jpg', '/userfiles/images/R5AT4212.jpg', '/userfiles/images/R5AT4215.jpg'],
            ]),
            'stats' => json_encode([
                ['value' => 10, 'description' => 'Năm kinh nghiệm', 'images' => '/userfiles/images/R5AT4219.jpg'],
                ['value' => 50, 'description' => 'Giáo viên ưu tú', 'images' => '/userfiles/images/R5AT4222.jpg'],
                ['value' => 2000, 'description' => 'Học viên theo học', 'images' => '/userfiles/images/R5AT4226.jpg'],
                ['value' => 95, 'description' => '% Phụ huynh hài lòng', 'images' => '/userfiles/images/R5AT4230.jpg'],
            ]),
            'fags' => json_encode([
                ['question' => 'Trung tâm có lớp học thử miễn phí không?', 'answer' => 'Có, chúng tôi có các buổi học thử định kỳ. Vui lòng để lại thông tin để được tư vấn lịch học gần nhất.'],
                ['question' => 'Lộ trình học cho bé được xây dựng như thế nào?', 'answer' => 'Mỗi học viên sẽ được kiểm tra đầu vào và tư vấn lộ trình cá nhân hóa để đảm bảo hiệu quả học tập tốt nhất.'],
                ['question' => 'Đội ngũ giáo viên của trung tâm có trình độ như thế nào?', 'answer' => '100% giáo viên tại AU English có bằng cấp sư phạm, chứng chỉ giảng dạy quốc tế (TESOL/IELTS) và nhiều năm kinh nghiệm.'],
            ]),
            'images' => json_encode(['/userfiles/images/R5AT4240.jpg', '/userfiles/images/R5AT4246.jpg', '/userfiles/images/R5AT4255.jpg']),
            'link_youtubes' => json_encode(['https://youtu.be/fXXcJJENN9U', 'https://youtu.be/BaR4iCqJFWk']),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}