<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $table = 'activity';

    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'latitude',
        'longitude',
        'country',
        'city',
        'csrf_token',
        'action'
    ];
}