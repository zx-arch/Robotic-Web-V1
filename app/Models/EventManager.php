<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class EventManager extends Model
{
    use SoftDeletes;

    protected $table = 'event_manager';

    protected $fillable = [
        'event_code',
        'name',
        'email',
        'section',
        'phone_number'
    ];

    protected static function boot()
    {
        parent::boot();

        static::checkAndCreateTable();
    }

    private static function checkAndCreateTable()
    {
        if (!Schema::hasTable('events')) {
            Schema::create('events', function (Blueprint $table) {
                $table->uuid('code')->primary();
                $table->string('nama_event', 100);
                $table->string('location', 100);
                $table->timestamp('event_date');
                $table->string('organizer_name', 100);
                $table->string('event_section', 50);
                $table->text('poster');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('event_manager')) {
            Schema::create('event_manager', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('event_code');
                $table->string('name', 50);
                $table->string('email', 100);
                $table->string('section', 50);
                $table->string('phone_number', 20);
                $table->timestamps();
                $table->softDeletes();

                // Tambahkan indeks untuk foreign key
                $table->index('event_code');

                // Definisikan foreign key
                $table->foreign('event_code')
                    ->references('code')
                    ->on('events')
                    ->onDelete('cascade');
            });
        }
    }

    public function events()
    {
        return $this->belongsTo(Events::class, 'event_code');
    }
}