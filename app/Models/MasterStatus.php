<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

class MasterStatus extends Model
{
    protected $table = 'master_status';
    protected $fillable = [
        'name',
        'description',
    ];
    protected static function boot()
    {
        parent::boot();

        static::checkAndCreateTable();
    }

    private static function checkAndCreateTable()
    {
        if (!Schema::hasTable('master_status')) {
            Schema::create('master_status', function (Blueprint $table) {
                $table->id();
                $table->string('name', 50);
                $table->string('description');
            });

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

        } elseif (MasterStatus::count() == 0) {
            $data = [
                ['name' => 'Enable Materi', 'description' => 'Status publikasi materi'],
                ['name' => 'Disable Materi', 'description' => 'Menonaktifkan materi yang ditampilkan untuk user'],
                ['name' => 'Draft', 'description' => 'Menyimpan sementara materi yang diupload'],
                ['name' => 'Active Tutorial', 'description' => 'Enable publikasi video tutorial'],
                ['name' => 'Disable Tutorial', 'description' => 'Disabled tutorial'],
                ['name' => 'Draft', 'description' => 'Draft'],
                ['name' => 'Enable Static Pages', 'description' => 'Khusus memperbarui komponen web'],
            ];

            DB::table('master_status')->insert($data);

            DB::table('master_status')->update([
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}