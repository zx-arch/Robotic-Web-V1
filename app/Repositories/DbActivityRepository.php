<?php

// app/Repositories/DbActivityRepository.php
namespace App\Repositories;

use App\Contracts\ActivityRepositoryInterface;
use GeoIp2\Database\Reader;

class DbActivityRepository implements ActivityRepositoryInterface
{
    public function getActivityInfo($ipAddress, $userAgent)
    {
        $activityInfo = session('myActivity');

        if (!$activityInfo) {
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

            $activityInfo = [
                'ip_address' => $ipAddress,
                'netmask' => $netmask,
                'user_agent' => $userAgent,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'country' => $countryName,
                'city' => $cityName . (isset($subdivisions) ? ', ' . $subdivisions : ''),
            ];

            session(['myActivity' => $activityInfo]);
        }

        return $activityInfo;
    }
}