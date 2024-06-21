<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class LikesDiscussion extends Model
{
    protected $table = 'likes_discussion';

    protected $fillable = [
        'discussion_id',
        'user_id',
        'is_clicked_like'
    ];
    protected static function boot()
    {
        parent::boot();

        static::checkAndCreateTable();
    }
    private static function checkAndCreateTable()
    {
        if (!in_array('likes_discussion', session('existingTables'))) {
            Schema::create('likes_discussion', function (Blueprint $table) {
                $table->unsignedBigInteger('id')->autoIncrement();
                $table->unsignedBigInteger('discussion_id');
                $table->unsignedBigInteger('user_id');
                $table->boolean('is_clicked_like')->default(false);
                $table->timestamps();
            });
        }
    }
}