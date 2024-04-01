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
        Schema::create('tutorials', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->string('video_name');
            $table->string('category');
            $table->unsignedBigInteger('tutorial_category_id');
            $table->text('thumbnail');
            $table->text('path_video');
            $table->boolean('is_shown')->default(true);
            $table->string('url');
            $table->unsignedBigInteger('status_id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutorials');
    }
};