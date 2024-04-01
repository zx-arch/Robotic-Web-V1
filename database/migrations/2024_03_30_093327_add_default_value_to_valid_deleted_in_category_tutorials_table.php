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
            $table->boolean('valid_deleted');
            $table->text('delete_html_code');
        });
        DB::table('category_tutorial')->update([
            'valid_deleted' => false,
            'delete_html_code' => '',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('category_tutorial', function (Blueprint $table) {
            $table->dropColumn('valid_deleted');
            $table->dropColumn('delete_html_code');

        });
    }
};