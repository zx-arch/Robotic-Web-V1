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

        static::checkAndCreateTable();

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

    private static function checkAndCreateTable()
    {
        if (!Schema::hasTable('activity')) {
            DB::statement('
                CREATE TABLE activity (
                    id bigint(20) UNSIGNED NOT NULL,
                    user_id bigint(20) UNSIGNED DEFAULT NULL,
                    ip_address varchar(255) DEFAULT NULL,
                    user_agent varchar(255) DEFAULT NULL,
                    latitude double(10,6) DEFAULT NULL,
                    longitude double(10,6) DEFAULT NULL,
                    country varchar(255) DEFAULT NULL,
                    city varchar(255) DEFAULT NULL,
                    csrf_token varchar(255) DEFAULT NULL,
                    endpoint varchar(100) DEFAULT NULL,
                    metadata longtext DEFAULT NULL,
                    action varchar(255) NOT NULL,
                    request_method varchar(10) NOT NULL, // Tambahkan kolom request_method
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ');

        } elseif (!Schema::hasColumn('activity', 'metadata')) {
            DB::statement('ALTER TABLE activity ADD COLUMN metadata longtext DEFAULT NULL');


        } elseif (!Schema::hasColumn('activity', 'endpoint')) {
            DB::statement('ALTER TABLE activity ADD COLUMN endpoint varchar(100) DEFAULT NULL');

        } elseif (!Schema::hasColumn('activity', 'request_method')) {
            DB::statement('ALTER TABLE activity ADD COLUMN request_method varchar(10) NOT NULL');
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
}