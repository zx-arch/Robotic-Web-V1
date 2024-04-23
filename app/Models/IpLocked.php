<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class IpLocked extends Model
{
    protected $table = 'ip_locked';

    protected $fillable = [
        'network',
    ];

    protected static function boot()
    {
        parent::boot();

        static::checkAndCreateTable();
    }

    private static function checkAndCreateTable()
    {
        if (!Schema::hasTable('ip_locked')) {
            Schema::create('ip_locked', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('network');
                $table->timestamps();
            });
        }
    }

}