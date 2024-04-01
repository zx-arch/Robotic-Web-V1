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
        Schema::table('category_tutorial', function (Blueprint $table) {
            $table->unsignedBigInteger('status_id')->nullable();
            $table->boolean('is_shown');
            $table->timestamps();
        });

        DB::table('category_tutorial')->update([
            'is_shown' => true,
            'status_id' => 11,
        ]);

        DB::table('category_tutorial')->update([
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('category_tutorial', function (Blueprint $table) {
            // Menghapus kolom status_id
            $table->dropColumn('status_id');
        });
    }
};