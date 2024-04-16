<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

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
        'endpoint',
        'metadata',
        'action',
        'request_method' // Tambahkan properti request_method
    ];

    protected static function boot()
    {
        parent::boot();

        // Event listener untuk mengisi endpoint jika ada request referer
        static::creating(function ($activity) {
            $referer = request()->header('referer', '');
            if (!empty ($referer)) {
                $activity->endpoint = $referer;
            }

            // Mendapatkan dan menambahkan informasi metode permintaan
            $activity->request_method = request()->method();

            $activity->csrf_token = $activity->generateCsrfToken();

        });

        static::deleting(function ($activity) {
            return false;
        });

        static::updating(function ($activity) {
            return false;
        });
    }

    // Nonaktifkan method update()
    public function update(array $attributes = [], array $options = [])
    {
        return false;
    }

    // Nonaktifkan method delete()
    public function delete()
    {
        return false;
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
}