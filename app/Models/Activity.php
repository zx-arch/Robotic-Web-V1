<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

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

            // Ambil informasi negara berdasarkan IP
            $activity->country = session('myActivity.country') ?? null;
            $activity->city = session('myActivity.city') ?? null;
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

    // Method untuk mendapatkan informasi negara berdasarkan alamat IP

    // Method untuk menghitung seberapa sering tingkat akses aktivitas user berdasarkan alamat IP
    public static function accessPercentageByIP()
    {
        $accessCounts = session('accessCounts');

        if (!$accessCounts) {
            // Query untuk menghitung frekuensi akses berdasarkan alamat IP
            $accessCounts = self::select('ip_address', 'country', 'city', 'latitude', 'longitude')
                ->selectRaw('count(*) as access_count')
                ->groupBy('ip_address', 'country', 'city', 'latitude', 'longitude')
                ->orderBy('access_count', 'desc')
                ->get();

            session(['accessCounts' => $accessCounts]);
        }

        // Hitung total akses
        $totalAccess = $accessCounts->sum('access_count');

        // Hitung presentase untuk setiap alamat IP
        $accessPercentageByIP = $accessCounts->map(function ($item) use ($totalAccess) {
            return [
                'ip_address' => $item->ip_address,
                'city' => $item->city,
                'country' => $item->country,
                'latitude' => $item->latitude,
                'longitude' => $item->longitude,
                'access_percentage' => ($item->access_count / $totalAccess) * 100,
                'total_access' => $item->access_count,
            ];
        });

        return $accessPercentageByIP;
    }
}