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

    public function events()
    {
        return $this->belongsTo(Events::class, 'event_code');
    }
}