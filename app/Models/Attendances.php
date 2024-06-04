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
}