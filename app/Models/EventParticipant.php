<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventParticipant extends Model
{
    use SoftDeletes;

    protected $table = 'event_participant';

    protected $fillable = [
        'event_code',
        'name',
        'email',
        'phone_number',
        'status_presensi',
        'waktu_presensi'
    ];

    public function events()
    {
        return $this->belongsTo(Events::class, 'event_code');
    }

    protected static function boot()
    {
        parent::boot();

        static::bootColumnsCheck();

        static::bootUpdateAbsentStatus();
    }

    protected static function bootColumnsCheck()
    {
        if (!Auth::check()) {
            try {
                // Memeriksa apakah tabel 'event_participant' ada di dalam daftar tabel yang ada
                if (in_array('event_participant', session('existingTables'))) {
                    // Cek apakah kolom 'status_presensi' ada dalam tabel 'event_participant'
                    $columnExists = DB::select(DB::raw("SHOW COLUMNS FROM `event_participant` LIKE 'status_presensi'"));

                    if (empty($columnExists)) {
                        // Jika kolom 'status_presensi' tidak ada, tambahkan kolom tersebut
                        Schema::table('event_participant', function (Blueprint $table) {
                            $table->enum('status_presensi', ['Hadir', 'Tidak Hadir'])->nullable()->default(null);
                        });
                    }

                    $columnExists = DB::select(DB::raw("SHOW COLUMNS FROM `event_participant` LIKE 'waktu_presensi'"));

                    if (empty($columnExists)) {
                        // Jika kolom 'waktu_presensi' tidak ada, tambahkan kolom tersebut
                        Schema::table('event_participant', function (Blueprint $table) {
                            $table->timestamp('waktu_presensi')->nullable()->default(null);
                        });
                    }
                }
            } catch (\Throwable $e) {
                return;
            }
        }
    }

    protected static function bootUpdateAbsentStatus()
    {
        static::retrieved(function ($model) {
            $now = Carbon::now();

            if (!Auth::check()) {
                // Find all events with a closing date before the current time
                $closedEvents = DB::table('attendances')
                    ->where('closing_date', '<', $now)
                    ->pluck('event_code')
                    ->toArray();

                if (!empty($closedEvents)) {
                    DB::table('event_participant')
                        ->whereIn('event_code', $closedEvents)
                        ->whereNull('status_presensi')
                        ->orWhere('status_presensi', '!=', 'Hadir')
                        ->update([
                            'status_presensi' => 'Tidak Hadir',
                            'waktu_presensi' => null,
                            'updated_at' => $now // Update the updated_at timestamp
                        ]);
                }
            }
        });
    }
}