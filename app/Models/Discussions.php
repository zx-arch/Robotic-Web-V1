<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class Discussions extends Model
{
    use SoftDeletes;
    protected $table = 'discussions';

    protected $fillable = [
        'title',
        'user_id',
        'message',
        'hashtags',
        'likes',
        'views',
        'responses',
        'gambar'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::checkAndCreateTable();

        static::creating(function ($model) {
            $model->likes = 0;
            $model->views = 0;
            $model->responses = 0;
        });
    }

    private static function checkAndCreateTable()
    {
        $existingTables = DB::table('information_schema.tables')
            ->select('table_name')
            ->where('table_schema', DB::connection()->getDatabaseName())
            ->pluck('table_name')
            ->toArray();
        session(['existingTables' => $existingTables]);

        if (!in_array('discussions', $existingTables)) {
            Schema::create('discussions', function (Blueprint $table) {
                $table->unsignedBigInteger('id')->autoIncrement();
                $table->unsignedBigInteger('user_id');
                $table->string('title', 100);
                $table->longText('message');
                $table->json('hashtags');
                $table->unsignedBigInteger('likes');
                $table->unsignedBigInteger('views');
                $table->unsignedBigInteger('responses');
                $table->string('gambar')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function incrementViewCount()
    {
        $this->views++;
        $this->save();
    }
}