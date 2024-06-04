<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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
        // Check if the column 'status_presensi' exists
        if (!Schema::hasColumn('event_participant', 'status_presensi')) {
            // Add the 'status_presensi' column
            Schema::table('event_participant', function (Blueprint $table) {
                $table->enum('status_presensi', ['Hadir', 'Tidak Hadir'])->nullable()->default(null);
            });
        }
        // Check if the column 'waktu_presensi' exists
        if (!Schema::hasColumn('event_participant', 'waktu_presensi')) {
            // Add the 'waktu_presensi' column
            Schema::table('event_participant', function (Blueprint $table) {
                $table->timestamp('waktu_presensi')->nullable()->default(null);
            });
        }
    }

    protected static function bootUpdateAbsentStatus()
    {
        static::retrieved(function ($model) {
            $now = Carbon::now();

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
        });
    }
}