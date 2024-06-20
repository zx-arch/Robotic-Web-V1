<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class Hashtags extends Model
{
    protected $table = 'hashtags';

    protected $fillable = [
        'tag_name',
        'count'
    ];
    protected static function boot()
    {
        parent::boot();

        static::checkAndCreateTable();

        static::saving(function ($model) {
            // Check if the hashtag already exists
            $existingTag = static::where('tag_name', $model->tag_name)->first();
            if ($existingTag) {
                // If the tag exists, increment the count
                $existingTag->increment('count');
                // Prevent saving the duplicate tag
                return false;
            } else {
                // If the tag does not exist, initialize count to 1
                $model->count = 1;
            }
        });

        static::deleting(function ($model) {
            // Decrement count saat model dihapus
            if ($model->count > 0) {
                $model->decrement('count');
            }
        });

        static::deleting(function ($model) {
            $model->decrement('count'); // Decrement count saat model dihapus
        });
    }
    private static function checkAndCreateTable()
    {
        if (!in_array('hashtags', session('existingTables'))) {
            Schema::create('hashtags', function (Blueprint $table) {
                $table->unsignedBigInteger('id')->autoIncrement();
                $table->string('tag_name', 50);
                $table->unsignedBigInteger('count')->default(0);
                $table->timestamps();
            });
        }
    }
}