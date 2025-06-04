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
        // Schema::create('news', function (Blueprint $table) {
        //     $table->uuid('id')->primary();
        //     $table->uuid('user_id');
        //     $table->string('title')->nullable();
        //     $table->string('image')->nullable();
        //     $table->text('content')->nullable();
        //     $table->integer('views')->unsigned()->default(0);
        //     $table->string('html')->nullable();
        //     $table->enum('status', ['draft', 'published'])->default('draft');
        //     $table->timestamps();
        //     $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        // });

        Schema::create('news', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('image')->nullable();
            $table->integer('views')->unsigned()->default(0);
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamps();
        
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        
        // Tabel terjemahan berita
        Schema::create('news_translations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('news_id');
            $table->string('locale');
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->string('html')->nullable();
            $table->timestamps();
            $table->foreign('news_id')->references('id')->on('news')->onDelete('cascade');
            $table->unique(['news_id', 'locale']);
        });

        // Table untuk kategori
        Schema::create('categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
        });

        Schema::create('categories_translation', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('locale');
            $table->uuid('category_id');
            $table->unique(['category_id', 'locale']);
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->timestamps();
        });

        // Table pivot untuk relasi many-to-many
        Schema::create('category_news', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('news_id');
            $table->uuid('category_id');
            $table->timestamps();
            $table->unique(['news_id', 'category_id']);
            $table->foreign('news_id')->references('id')->on('news')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('category_news');
        Schema::dropIfExists('news_translations');
        Schema::dropIfExists('categories_translation');
    }
};
