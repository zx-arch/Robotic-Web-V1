<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class DiscussionsAnswer extends Model
{
    protected $table = 'discussions_answer';
    protected $fillable = [
        'discussion_id',
        'user_id',
        'message',
        'like',
        'reply_user_id',
        'is_clicked_like',
        'gambar',
    ];

    protected static function boot()
    {
        parent::boot();

        static::checkAndCreateTable();
    }

    private static function checkAndCreateTable()
    {

        if (!in_array('discussions_answer', session('existingTables'))) {
            Schema::create('discussions_answer', function (Blueprint $table) {
                $table->unsignedBigInteger('id')->autoIncrement();
                $table->unsignedBigInteger('discussion_id');
                $table->unsignedBigInteger('user_id');
                $table->longText('message');
                $table->boolean('like')->default(false);
                $table->unsignedBigInteger('reply_user_id')->nullable(); // Mengizinkan nilai null
                $table->string('gambar')->nullable();
                $table->timestamps();
            });
        }

        $columnExists = DB::select(DB::raw("SHOW COLUMNS FROM `discussions_answer` LIKE 'is_clicked_like'"));

        if (empty($columnExists)) {
            // Jika kolom 'link_online' tidak ada, tambahkan kolom tersebut
            Schema::table('discussions_answer', function (Blueprint $table) {
                $table->boolean('is_clicked_like')->default(false)->after('like');
            });
            DB::statement("ALTER TABLE discussions_answer CHANGE COLUMN `like` `like` BIGINT(20)");
        }

    }

    public function replies()
    {
        return $this->hasMany(DiscussionsAnswer::class, 'reply_user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}