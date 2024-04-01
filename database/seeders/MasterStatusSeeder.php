<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (DB::table('master_status')->count() > 0) {
            // Jika sudah ada, hapus semua data dari tabel
            DB::table('master_status')->truncate();
        }

        $data = [
            ['name' => 'Enable Materi', 'description' => 'Status publikasi materi'],
            ['name' => 'Disable Materi', 'description' => 'Menonaktifkan materi yang ditampilkan untuk user'],
            ['name' => 'Draft', 'description' => 'Menyimpan sementara materi yang diupload'],
            ['name' => 'Enable', 'description' => 'Enable publikasi video tutorial'],
            ['name' => 'Disable', 'description' => 'Disabled tutorial'],
            ['name' => 'Draft', 'description' => 'Draft'],
            ['name' => 'Enable Static Pages', 'description' => 'Khusus memperbarui komponen web'],
            ['name' => 'Enable', 'description' => 'Authentikasi Login active'],
            ['name' => 'Disable', 'description' => 'Non-active Authentikasi User Login'],
            ['name' => 'Pending', 'description' => 'Status user baru mendaftar'],
            ['name' => 'Enable', 'description' => 'Enable category tutorial'],
            ['name' => 'Disable', 'description' => 'Disable category tutorial'],
        ];

        DB::table('master_status')->insert($data);

        DB::table('master_status')->update([
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}