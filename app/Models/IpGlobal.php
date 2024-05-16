<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Repositories\IpGlobalRepository;
use App\Services\IpGlobalService;

class IpGlobal extends Model
{
    protected $table = 'ip_global';

    protected $fillable = [
        'network',
        'netmask',
        'geoname_id',
        'continent_code',
        'continent_name',
        'country_iso_code',
        'country_name',
        'is_anonymous_proxy',
        'is_satellite_provider',
        'is_blocked',
    ];

    protected $ipGlobalService;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->ipGlobalService = app(IpGlobalService::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::checkAndCreateTable();

        static::created(function ($model) {
            $model->ipGlobalService->recordIfNotExists($model->network);
        });
    }

    public static function checkAndCreateTable()
    {
        if (!Schema::hasTable('ip_global')) {
            Schema::create('ip_global', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('network');
                $table->string('netmask');
                $table->string('geoname_id');
                $table->string('continent_code');
                $table->string('continent_name');
                $table->string('country_iso_code');
                $table->string('country_name');
                $table->boolean('is_anonymous_proxy');
                $table->boolean('is_satellite_provider');
                $table->boolean('is_blocked')->default(false);
                $table->timestamps();
            });
        }
    }

    public static function calculateNetmask($network)
    {
        try {
            $cidr = explode('/', $network)[1];
            $netmask = str_repeat('1', $cidr) . str_repeat('0', 32 - $cidr);
            $netmaskParts = str_split($netmask, 8);
            $netmaskIP = array_map(function ($part) {
                return bindec($part);
            }, $netmaskParts);

            return implode('.', $netmaskIP);
        } catch (\Throwable $e) {
            return null;
        }
    }
}