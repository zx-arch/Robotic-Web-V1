<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class ResponsesDiscussions extends Model
{
    use SoftDeletes;
    protected $table = 'discussion_answer';
    protected $fillable = [
        'discussion_id',
        'user_id',
        'message',
        'like_post',
        'views',
        'responses',
    ];
}