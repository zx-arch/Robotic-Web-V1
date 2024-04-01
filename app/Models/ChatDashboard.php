<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

class ChatDashboard extends Model
{
    use SoftDeletes;
    protected $table = 'chat_dashboard';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message'
    ];

    protected static function boot()
    {
        parent::boot();

        static::checkAndCreateTable();

        static::creating(function ($model) {
            $model->csrf_token = $model->generateCsrfToken();
        });
    }

    private static function checkAndCreateTable()
    {
        if (!Schema::hasTable('chat_dashboard')) {
            Schema::create('chat_dashboard', function (Blueprint $table) {
                $table->unsignedBigInteger('id')->autoIncrement();
                $table->string('name');
                $table->string('email');
                $table->string('subject');
                $table->text('message');
                $table->string('csrf_token')->nullable();
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->nullable();
                $table->softDeletes();
            });
        }
    }

    public function generateCsrfToken()
    {
        // Cek apakah token CSRF sudah ada dalam sesi
        $csrfToken = Session::get('csrf_token');

        // Jika belum ada, buat token baru
        if (!$csrfToken) {
            $csrfToken = Str::random(60);
            Session::put('csrf_token', $csrfToken);
        }

        return $csrfToken;
    }

    public static function checkCsrfTokenUsage($csrfToken)
    {
        // Hitung jumlah penggunaan token CSRF dalam tabel
        $count = static::where('csrf_token', $csrfToken)->count();

        // Jika jumlah penggunaan lebih dari 5, kembalikan pesan error
        if ($count > 10) {
            return "Terlalu sering submit chat!";
        }

        return null;
    }

}