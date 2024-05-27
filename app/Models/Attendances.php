<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class Attendances extends Model
{
    use SoftDeletes;
    protected $table = 'attendances';
    protected $fillable = [
        'event_code',
        'event_name',
        'status',
        'opening_date',
        'closing_date',
        'access_code',
    ];

    protected static function boot()
    {
        parent::boot();

        static::checkAndCreateTable();
    }

    private static function checkAndCreateTable()
    {
        if (!Schema::hasTable('attendances')) {
            Schema::create('attendances', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->uuid('event_code');
                $table->string('event_name');
                $table->enum('status', ['Enable', 'Disable'])->nullable();
                $table->timestamp('opening_date')->nullable();
                $table->timestamp('closing_date')->nullable();
                $table->char('access_code', 15);
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }
}