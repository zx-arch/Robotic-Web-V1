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

        if (!Schema::hasTable('discussions_answer')) {
            Schema::create('discussions_answer', function (Blueprint $table) {
                $table->id(); // Menggunakan auto-incrementing primary key secara otomatis
                $table->unsignedBigInteger('discussion_id');
                $table->unsignedBigInteger('user_id');
                $table->longText('message');
                $table->boolean('like')->default(false);
                $table->unsignedBigInteger('reply_user_id')->nullable(); // Mengizinkan nilai null
                $table->string('gambar')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'discussion_id']);

                // Menambahkan foreign key constraints
                $table->foreign('discussion_id')
                    ->references('id')
                    ->on('discussions')
                    ->onDelete('cascade');

                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
            });

        } else {
            $checkForeignKey = DB::select(DB::raw("SELECT * FROM INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS 
            WHERE CONSTRAINT_NAME = 'fk_discussions_answer_discussion_id' OR CONSTRAINT_NAME = 'fk_discussions_answer_user_id'"));

            if (!$checkForeignKey) {
                DB::statement('ALTER TABLE discussions_answer
                ADD CONSTRAINT fk_discussions_answer_discussion_id
                FOREIGN KEY (discussion_id) REFERENCES discussions(id)
                ON DELETE CASCADE');

                // Tambahkan foreign key untuk user_id
                DB::statement('ALTER TABLE discussions_answer
                ADD CONSTRAINT fk_discussions_answer_user_id
                FOREIGN KEY (user_id) REFERENCES users(id)
                ON DELETE CASCADE');
            }
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