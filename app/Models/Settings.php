<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

class Settings extends Model
{
    use SoftDeletes;

    protected $table = 'settings';

    protected $fillable = [
        'user_id',
        'nama_pengelola',
        'email_pengelola',
        'instansi',
        'jabatan',
        'foto_profil'
    ];

    protected static function boot()
    {
        parent::boot();

        static::checkAndCreateTable();
    }

    private static function checkAndCreateTable()
    {
        if (!Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->string('nama_pengelola', 100)->nullable();
                $table->string('email_pengelola', 70)->nullable();
                $table->string('instansi')->nullable();
                $table->string('jabatan', 50)->nullable();
                $table->string('foto_profil')->nullable();
                $table->timestamps();
                $table->softDeletes(); // Soft delete column
            });
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}