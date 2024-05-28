<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class EventParticipant extends Model
{
    use SoftDeletes;

    protected $table = 'event_participant';

    protected $fillable = [
        'event_code',
        'name',
        'email',
        'phone_number'
    ];

    public function events()
    {
        return $this->belongsTo(Events::class, 'event_code');
    }
}