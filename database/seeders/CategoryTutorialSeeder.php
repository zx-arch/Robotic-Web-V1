<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryTutorialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (DB::table('category_tutorial')->count() > 0) {
            // Jika sudah ada, hapus semua data dari tabel
            DB::table('category_tutorial')->truncate();
        }

        DB::table('category_tutorial')->insert([
            'category' => 'Product Video',
            'status_id' => 11,
            'valid_deleted' => false,
            'delete_html_code' => '',
        ]);

        DB::table('category_tutorial')->insert([
            'category' => 'Software Requirement',
            'status_id' => 11,
            'valid_deleted' => false,
            'delete_html_code' => '',
        ]);

        DB::table('category_tutorial')->update([
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}