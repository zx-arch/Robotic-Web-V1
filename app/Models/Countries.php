<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Countries extends Model
{
    protected $table = 'countries';
    protected $fillable = [
        'name_common',
        'name_official',
        'tld',
        'cca2',
        'ccn3',
        'cca3',
        'cioc',
        'independent',
        'status',
        'unMember',
        'currencies',
        'idd',
        'capital',
        'altSpellings',
        'region',
        'subregion',
        'languages',
        'translations',
        'latlng',
        'landlocked',
        'borders',
        'area',
        'flag',
        'demonyms',
    ];

    /**
     * Boot the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // Ketika model dimuat, periksa apakah tabel belum memiliki data.
        // Jika belum, masukkan data dari file JSON.
        static::checkAndSeedData();
    }

    /**
     * Periksa apakah tabel belum memiliki data.
     * Jika belum, masukkan data dari file JSON.
     *
     * @return void
     */
    public static function checkAndSeedData()
    {
        if (self::doesntExist()) {
            $json = File::get(public_path('language/countries.json'));
            $data = json_decode($json, true);

            // Loop untuk memasukkan data ke dalam tabel countries
            foreach ($data as $country) {
                self::create([
                    'name_common' => $country['name']['common'],
                    'name_official' => $country['name']['official'],
                    'tld' => json_encode($country['tld']),
                    'cca2' => $country['cca2'],
                    'ccn3' => $country['ccn3'],
                    'cca3' => $country['cca3'],
                    'cioc' => $country['cioc'],
                    'independent' => $country['independent'] ?? true,
                    'status' => $country['status'],
                    'unMember' => $country['unMember'],
                    'currencies' => json_encode($country['currencies']),
                    'idd' => json_encode($country['idd']),
                    'capital' => json_encode($country['capital']),
                    'altSpellings' => json_encode($country['altSpellings']),
                    'region' => $country['region'],
                    'subregion' => $country['subregion'],
                    'languages' => json_encode($country['languages']),
                    'translations' => json_encode($country['translations']),
                    'latlng' => json_encode($country['latlng']),
                    'landlocked' => $country['landlocked'],
                    'borders' => json_encode($country['borders']),
                    'area' => $country['area'],
                    'flag' => $country['flag'],
                    'demonyms' => json_encode($country['demonyms']),
                ]);
            }
        }
    }
}