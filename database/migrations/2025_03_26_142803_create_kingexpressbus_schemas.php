<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->integer('count')->default(0);
            $table->timestamps();
        });

        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('excerpt');
            $table->string('thumbnail');
            $table->string('author');
            $table->integer('view')->default(0);
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->longText('content');
            $table->timestamps();
        });

        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->integer('priority')->default(99);
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('age');
            $table->longText('description');
            $table->string('thumbnail');
            $table->string('duration');
            $table->string('outcome');
            $table->string('method');
            $table->text('speaking');
            $table->text('listening');
            $table->text('reading');
            $table->text('writing');
            $table->longText('content')->nullable();
            $table->text('images')->nullable();
            $table->longText('curriculum')->nullable();
            $table->text('youtube_review_link')->nullable();
            $table->timestamps();
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')->nullable()->constrained('trainings')->onDelete('set null');
            $table->string('full_name_parent');
            $table->string('phone', 10);
            $table->string('email', 50);
            $table->string('full_name_children');
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->date('date_of_birth');
            $table->text('address');
            $table->longText('note')->nullable();
            $table->timestamps();
        });

        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->integer('priority')->default(99);
            $table->string('full_name');
            $table->string('role');
            $table->text('qualifications');
            $table->string('avatar');
            $table->string('slug')->unique();
            $table->string('facebook');
            $table->string('email', 50)->unique();
            $table->longText('description')->nullable();
            $table->timestamps();
        });

        Schema::create('home_page', function (Blueprint $table) {
            $table->id();
            $table->text('banners')->unique();
            $table->text('stats')->nullable();
            $table->text('fags')->nullable();
            $table->text('images')->nullable();
            $table->text('link_youtubes')->unique();
            $table->timestamps();
        });

        Schema::create('contact', function (Blueprint $table) {
            $table->id();
            $table->longText('address');
            $table->string('phone', 10);
            $table->string('email', 50);
            $table->string('facebook', 50);
            $table->timestamps();
        });

        Schema::create('parents_corner', function (Blueprint $table) {
            $table->id();
            $table->integer('priority')->default(99);
            $table->string('slug')->unique();
            $table->longText('image');
            $table->text('rate');
            $table->string('name');
            $table->string('describe', 50);
            $table->longText('content');
            $table->timestamps();
        });

        Schema::create('document', function (Blueprint $table) {
            $table->id();
            $table->integer('priority')->default(99);
            $table->string('name', 50);
            $table->text('src');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
        Schema::dropIfExists('news');
        Schema::dropIfExists('trainings');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('teachers');
        Schema::dropIfExists('home_page');
        Schema::dropIfExists('contact');
        Schema::dropIfExists('parents_corner');
        Schema::dropIfExists('document');
    }
};