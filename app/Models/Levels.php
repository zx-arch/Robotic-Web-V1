<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

class Levels extends Model
{
    use SoftDeletes;

    protected $table = 'levels';

    protected $fillable = [
        'level_name',
        'language_id',
        'hierarchy_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::checkAndCreateTable();
    }

    private static function checkAndCreateTable()
    {
        if (!Schema::hasTable('levels')) {
            Schema::create('levels', function (Blueprint $table) {
                $table->id();
                $table->string('level_name', 50);
                $table->unsignedBigInteger('language_id');
                $table->unsignedBigInteger('hierarchy_id');
                $table->timestamps();
                $table->softDeletes(); // Soft delete column
            });

            $data = [
                ['level_name' => 'Basic', 'language_id' => 7, 'hierarchy_id' => 3],
                ['level_name' => 'Intermediate', 'language_id' => 7, 'hierarchy_id' => 4],
                ['level_name' => 'Advanced', 'language_id' => 7, 'hierarchy_id' => 5],
                ['level_name' => 'Dasar', 'language_id' => 67, 'hierarchy_id' => 30],
                ['level_name' => 'Menengah', 'language_id' => 67, 'hierarchy_id' => 31],
                ['level_name' => 'Lanjutan', 'language_id' => 67, 'hierarchy_id' => 32],
            ];

            DB::table('levels')->insert($data);

            DB::table('levels')->update([
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        }
    }

    public function translations()
    {
        return $this->belongsTo(Translations::class, 'language_id');
    }
}