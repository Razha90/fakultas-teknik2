<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('path')->nullable();
            $table->string('type')->nullable();
            $table->uuid('page_id')->nullable();
            $table->foreign('page_id')->references('id')->on('pages');
            $table->uuid('news_id')->nullable();
            $table->foreign('news_id')->references('id')->on('news');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
