<?php

// app/Repositories/DbActivityRepository.php
namespace App\Repositories;

use App\Interfaces\ActivityRepositoryInterface;
use GeoIp2\Database\Reader;
use App\Models\Activity;
use Illuminate\Support\Facades\DB;

class DbActivityRepository implements ActivityRepositoryInterface
{
    protected $activityInfo;

    public function __construct()
    {
        $this->setActivityInfo();
    }

    protected function setActivityInfo()
    {
        $ipAddress = request()->ip();
        $userAgent = request()->header('User-Agent');

        $databasePath = public_path('GeoLite2-City.mmdb');
        $reader = new Reader($databasePath);

        if ($ipAddress == '127.0.0.1') {
            $ipAddress = '103.169.39.38';
        }

        $record = $reader->city($ipAddress);
        $netmask = $record->traits->network;
        $cityName = $record->city->name;
        $countryName = $record->country->name;
        $latitude = $record->location->latitude;
        $longitude = $record->location->longitude;
        $subdivisions = $record->subdivisions[0]->names['de'];

        $this->activityInfo = [
            'ip_address' => $ipAddress,
            'netmask' => $netmask,
            'user_agent' => $userAgent,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'country' => $countryName,
            'city' => $cityName . (isset($subdivisions) ? ', ' . $subdivisions : ''),
        ];

        session(['myActivity' => $this->activityInfo]);
    }

    public function create(array $data)
    {
        try {
            return Activity::create(array_merge(session('myActivity'), $data));

        } catch (\Throwable $e) {
            return Activity::create(array_merge(session('myActivity')));
        }
    }

}