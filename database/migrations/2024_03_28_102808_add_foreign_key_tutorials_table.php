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
        Schema::table('tutorials', function (Blueprint $table) {
            // Menambahkan foreign key
            $table->foreign('tutorial_category_id')->references('id')->on('category_tutorial')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tutorials', function (Blueprint $table) {
            // Menghapus foreign key jika perlu
            $table->dropForeign(['tutorial_category_id']);
        });
    }
};