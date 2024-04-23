<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use SplFileObject;

class ListIP extends Model
{
    protected $table = 'list_ip';

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

    protected static function boot()
    {
        parent::boot();

        static::checkAndCreateTable();

        static::recordIfNotExists(session('myActivity.netmask'));
    }

    private static function checkAndCreateTable()
    {
        if (!Schema::hasTable('list_ip')) {
            Schema::create('list_ip', function (Blueprint $table) {
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

            self::recordFromCSV();
        }
    }

    private static function recordFromCSV()
    {
        $filePath = public_path('geoip2-ipv4.csv');
        $file = new SplFileObject($filePath, 'r');
        $file->setFlags(SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY);
        $file->setCsvControl(','); // Set separator

        $rowIndex = 0;

        foreach ($file as $row) {
            $dt = str_getcsv($row[0]); // Menggunakan str_getcsv() untuk memisahkan nilai dengan koma
            if ($rowIndex > 0) {
                $ip = self::where('network', explode('/', $dt[0])[0])->first();
                if (!$ip) {
                    self::create([
                        'network' => explode('/', $dt[0])[0],
                        'geoname_id' => is_numeric($dt[1]) ? (int) trim($dt[1], '"') : 0, // Menghilangkan tanda kutip ganda
                        'continent_code' => $dt[2],
                        'continent_name' => $dt[3],
                        'country_iso_code' => $dt[4],
                        'country_name' => $dt[5],
                        'is_anonymous_proxy' => ($dt[6] === '1') ? true : false,
                        'is_satellite_provider' => ($dt[7] === '1') ? true : false,
                        'netmask' => self::calculateNetmask($dt[0]), // Memanggil method statis calculateNetmask
                    ]);
                }
            }
            $rowIndex++;
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

    public static function recordIfNotExists($ip)
    {
        try {
            $existingIP = self::where('network', session('myActivity.ip_address'))->first();

            if (!$existingIP) {

                $countryName = session('myActivity.country');
                $countryInfo = self::select('geoname_id', 'continent_code', 'continent_name', 'country_iso_code', 'is_anonymous_proxy', 'is_satellite_provider', 'is_blocked')
                    ->where('country_name', $countryName)
                    ->first();

                self::create([
                    'network' => session('myActivity.ip_address'),
                    'geoname_id' => $countryInfo->geoname_id ?? '0',
                    'continent_code' => $countryInfo->continent_code ?? '',
                    'continent_name' => $countryInfo->continent_name ?? '',
                    'country_iso_code' => $countryInfo->country_iso_code ?? '',
                    'country_name' => $countryName,
                    'is_anonymous_proxy' => $countryInfo->is_anonymous_proxy ?? false,
                    'is_satellite_provider' => $countryInfo->is_satellite_provider ?? false,
                    'is_blocked' => $countryInfo->is_blocked ?? false,
                    'netmask' => self::calculateNetmask($ip),
                ]);
            }

        } catch (\Throwable $e) {

            if (!is_null($ip)) {
                $existingIP = self::where('network', $ip)->first();
            }

            if (!$existingIP) {

                $countryName = session('myActivity.country');
                $countryInfo = self::select('geoname_id', 'continent_code', 'continent_name', 'country_iso_code', 'is_anonymous_proxy', 'is_satellite_provider', 'is_blocked')
                    ->where('country_name', $countryName)
                    ->first();

                self::create([
                    'network' => $ip,
                    'geoname_id' => $countryInfo->geoname_id ?? '0',
                    'continent_code' => $countryInfo->continent_code ?? '',
                    'continent_name' => $countryInfo->continent_name ?? '',
                    'country_iso_code' => $countryInfo->country_iso_code ?? '',
                    'country_name' => $countryName,
                    'is_anonymous_proxy' => $countryInfo->is_anonymous_proxy ?? false,
                    'is_satellite_provider' => $countryInfo->is_satellite_provider ?? false,
                    'is_blocked' => $countryInfo->is_blocked ?? false,
                    'netmask' => self::calculateNetmask($ip),
                ]);
            }
        }
    }

}