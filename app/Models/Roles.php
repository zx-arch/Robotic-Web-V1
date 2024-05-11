<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

class Roles extends Model
{
    use SoftDeletes;

    protected $table = 'roles';

    protected $fillable = [
        'name',
    ];

    protected static function boot()
    {
        parent::boot();

        static::checkAndCreateTable();
    }

    private static function checkAndCreateTable()
    {
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name', 50);
                $table->timestamps();
                $table->softDeletes(); // Soft delete column
            });

            $data = [
                ['name' => 'admin'],
                ['name' => 'pengurus'],
                ['name' => 'user'],
            ];

            DB::table('roles')->insert($data);

            DB::table('roles')->update([
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        }
    }
}