<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create web_info table
        Schema::create('web_info', function (Blueprint $table) {
            $table->id();
            $table->string('logo')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('hotline')->nullable();
            $table->string('phone_detail')->nullable();
            $table->string('web_link')->nullable();
            $table->string('facebook')->nullable();
            $table->string('zalo')->nullable();
            $table->string('address')->nullable();
            $table->text('map')->nullable();
            $table->longText('policy')->nullable();
            $table->text('detail')->nullable();
            $table->nullableTimestamps();
        });


        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('count')->default(0);
            $table->timestamps();
        });

        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('thumbnail');
            $table->string('author');
            $table->integer('view')->default(0);
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->longText('content');
            $table->timestamps();
        });

        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
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
            $table->text('curriculum')->nullable();
            $table->timestamps();
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')->nullable()->constrained('trainings')->onDelete('set null');
            $table->string('full_name_parent');
            $table->string('phone', 10);
            $table->string('email', 50);
            $table->string('full_name_children');
            $table->date('date_of_birth');
            $table->text('address');
            $table->longText('note')->nullable();
            $table->timestamps();
        });

        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('role');
            $table->text('qualifications');
            $table->string('avatar');
            $table->string('facebook');
            $table->string('email', 50)->unique();
            $table->longText('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
        Schema::dropIfExists('news');
        Schema::dropIfExists('trainings');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('teachers');
        Schema::dropIfExists('web_info');
    }
};
