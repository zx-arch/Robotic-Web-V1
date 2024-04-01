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
        Schema::create('levels', function (Blueprint $table) {
            $table->id();
            $table->string('level_name', 50);
            $table->unsignedBigInteger('language_id');
            $table->unsignedBigInteger('hierarchy_id');
            $table->timestamps();
            $table->softDeletes(); // Soft delete column
        });

        $data = [
            ['level_name' => 'Basic', 'language_id' => '7', 'hierarchy_id' => 3],
            ['level_name' => 'Intermediate', 'language_id' => '7', 'hierarchy_id' => 4],
            ['level_name' => 'Advanced', 'language_id' => '7', 'hierarchy_id' => 5],
            ['level_name' => 'Dasar', 'language_id' => '67', 'hierarchy_id' => 30],
            ['level_name' => 'Menengah', 'language_id' => '67', 'hierarchy_id' => 31],
            ['level_name' => 'Lanjutan', 'language_id' => '67', 'hierarchy_id' => 32],
            ['level_name' => 'Chapter 1', 'language_id' => '7', 'hierarchy_id' => 36],
            ['level_name' => 'Chapter 2', 'language_id' => '7', 'hierarchy_id' => 37],
            ['level_name' => 'Chapter 3', 'language_id' => '7', 'hierarchy_id' => 38],
            ['level_name' => 'Chapter 4', 'language_id' => '7', 'hierarchy_id' => 39],
            ['level_name' => 'Chapter 5', 'language_id' => '7', 'hierarchy_id' => 40],
            ['level_name' => 'Chapter 6', 'language_id' => '7', 'hierarchy_id' => 41],
            ['level_name' => 'Chapter 7', 'language_id' => '7', 'hierarchy_id' => 42],
        ];

        DB::table('levels')->insert($data);

        DB::table('levels')->update([
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('levels');
    }
};