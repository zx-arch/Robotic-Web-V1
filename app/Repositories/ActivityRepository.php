<?php

// app/Repositories/DbActivityRepository.php
namespace App\Repositories;

use App\Abstracts\ActivityAbstract;
use GeoIp2\Database\Reader;
use App\Models\Activity;
use Illuminate\Support\Facades\DB;

class ActivityRepository extends ActivityAbstract
{
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

    public static function create(array $data)
    {
        try {
            return Activity::create(array_merge(session('myActivity'), $data));

        } catch (\Throwable $e) {
            return Activity::create(array_merge(session('myActivity')));
        }
    }

    public static function getAccessData()
    {
        $accessPercentageByIP = Activity::accessPercentageByIP();
        $accessByCity = $accessPercentageByIP->first();
        $countActivity = $accessPercentageByIP->sum('total_access');
        $totalAccessDevice = $accessPercentageByIP->count(); // Menghitung jumlah alamat IP unik
        $highestAccess = $accessPercentageByIP->max('access_percentage'); // Menghitung persentase akses tertinggi

        return compact('accessPercentageByIP', 'countActivity', 'totalAccessDevice', 'accessByCity', 'highestAccess');
    }

    public static function searchAccessData($searchData)
    {
        $query = Activity::query();

        if (!empty($searchData['ip_address'])) {
            $query->where('ip_address', 'like', "{$searchData['ip_address']}%");
        }
        if (!empty($searchData['district'])) {
            $query->where('city', 'like', "{$searchData['district']}%");
        }
        if (!empty($searchData['province'])) {
            $query->where('city', 'like', "%{$searchData['province']}%");
        }
        if (!empty($searchData['country'])) {
            $query->where('country', 'like', "{$searchData['country']}%");
        }

        $accessCounts = $query->select('ip_address', 'country', 'city', 'latitude', 'longitude')
            ->selectRaw('count(*) as access_count')
            ->groupBy('ip_address', 'country', 'city', 'latitude', 'longitude')
            ->orderBy('access_count', 'desc')
            ->get();

        $totalAccess = $accessCounts->sum('access_count');

        $accessPercentageByIP = $accessCounts->map(function ($item) use ($totalAccess) {
            return [
                'ip_address' => $item->ip_address,
                'city' => $item->city,
                'country' => $item->country,
                'access_percentage' => ($item->access_count / $totalAccess) * 100,
                'latitude' => $item->latitude,
                'longitude' => $item->longitude,
                'total_access' => $item->access_count,
            ];
        });

        $accessByCity = $accessPercentageByIP->first();
        $countActivity = $accessPercentageByIP->sum('total_access');
        $totalAccessDevice = $accessPercentageByIP->count();
        $highestAccess = $accessPercentageByIP->max('access_percentage');

        return compact(
            'accessPercentageByIP',
            'countActivity',
            'totalAccessDevice',
            'accessByCity',
            'highestAccess'
        );
    }
}