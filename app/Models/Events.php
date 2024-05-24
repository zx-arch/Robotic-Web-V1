<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;

class Events extends Model
{
    use SoftDeletes;

    protected $table = 'events';
    protected $primaryKey = 'code'; // Tentukan primary key sebagai code
    public $incrementing = false; // Set $incrementing menjadi false karena primary key bukan integer

    protected $fillable = [
        'nama_event',
        'location',
        'event_date',
        'organizer_name',
        'event_section',
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
        if (!Schema::hasTable('events')) {
            Schema::create('events', function (Blueprint $table) {
                $table->uuid('code')->primary();
                $table->string('nama_event', 100);
                $table->string('location', 100);
                $table->timestamp('event_date')->nullable();
                $table->string('organizer_name', 100);
                $table->string('event_section', 50);
                $table->string('poster')->nullable();
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
        if (!Schema::hasTable('event_participant')) {
            Schema::create('event_participant', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('event_code');
                $table->string('name', 50);
                $table->string('email', 100);
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
}