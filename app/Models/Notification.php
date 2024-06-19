<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Notification extends Model
{
    use SoftDeletes;

    protected $table = 'notification';
    protected $fillable = [
        'user_id', // user yang menerima notifikasi
        'title', // judul notifikasi
        'content', // isi notifikasi (isi ini juga dapat berisi tag html misal ingin ada text tebal dst)
        'read', // status notifikasi ketika telah dibuka atau dibaca user
        'date_read', // tanggal dan jam notifikasi dibuka atau dibaca user
        'event_code',
        'redirect', // arahkan akses link ketika notifikasi diklik
        'link_online',
        'id_access',
        'passcode'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($notification) {
            static::removeDuplicateLinkOnline($notification->link_online);
        });

        static::updating(function ($notification) {
            static::removeDuplicateLinkOnline($notification->link_online);
        });
    }

    public static function removeDuplicateLinkOnline($link_online)
    {

        $duplicates = Notification::where('link_online', $link_online)->where('user_id', Auth::user()->id)->get();

        if ($duplicates->count() > 1) {
            // Keep the first one and delete the rest
            $duplicates->slice(1)->each(function ($duplicate) {
                $duplicate->forceDelete();
            });

        }
    }
}