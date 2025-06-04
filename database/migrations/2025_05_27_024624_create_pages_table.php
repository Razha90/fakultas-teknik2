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
        Schema::create('menus', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('name')->nullable();
            $table->string('path')->nullable();
            $table->integer('position')->nullable();
            $table->boolean('isActive')->default(true);
            $table->timestamps();
        });

        Schema::create('menus_translations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('menus_id');
            $table->foreign('menus_id')->references('id')->on('menus')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('locale');
            $table->unique(columns: ['menus_id', 'locale']);
            $table->timestamps();
        });

        Schema::create('pages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->uuid('menu_id')->nullable();
            $table->foreign('menu_id')->references('id')->on('menus');
            $table->string('html')->nullable();
            $table->string('name')->nullable();
            $table->string('data')->nullable();
            $table->string('path')->nullable();
            $table->timestamp('release')->nullable();
            $table->boolean('isReleased')->default(false);
            $table->string('keywords')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
        Schema::dropIfExists('pages');
    }
};
