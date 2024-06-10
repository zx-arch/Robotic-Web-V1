<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class OnlineEvents extends Model
{
    use SoftDeletes;
    protected $table = 'online_events';
    protected $primaryKey = 'code'; // Tentukan primary key sebagai code
    public $incrementing = false; // Set $incrementing menjadi false karena primary key bukan integer

    protected $fillable = [
        'code',
        'name',
        'host',
        'speakers',
        'event_date',
        'link_pendaftaran',
        'link_online',
        'user_access',
        'passcode',
        'online_app',
        'poster',
    ];

    protected static function boot()
    {
        parent::boot();

        static::checkAndCreateTable();

        static::creating(function ($model) {
            // Tetapkan nilai UUID baru saat membuat model baru
            $model->code = Str::uuid();
        });
    }

    private static function checkAndCreateTable()
    {
        if (!in_array('online_events', session('existingTables'))) {
            Schema::create('online_events', function (Blueprint $table) {
                $table->uuid('code')->primary();
                $table->string('name', 100);
                $table->string('host', 100);
                $table->text('speakers');
                $table->timestamp('event_date');
                $table->string('link_pendaftaran', 100)->nullable();
                $table->string('link_online', 100)->nullable();
                $table->string('user_access', 50)->nullable();
                $table->string('passcode')->nullable();
                $table->string('online_app', 100);
                $table->string('poster')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }
}