<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('category_tutorial', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('category');
        });

        DB::table('category_tutorial')->insert([
            'category' => 'Product Video',
        ]);

        DB::table('category_tutorial')->insert([
            'category' => 'Software Requirement',
        ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_tutorial');
    }
};