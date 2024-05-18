<?php

namespace App\Repositories;

use App\Abstracts\IpGlobalAbstract;
use App\Models\IpGlobal;

class IpGlobalRepository extends IpGlobalAbstract
{
    public function findByNetwork($network)
    {
        return IpGlobal::where('network', $network)->first();
    }

    public function findByCountryName($countryName)
    {
        return IpGlobal::select('geoname_id', 'continent_code', 'continent_name', 'country_iso_code', 'is_anonymous_proxy', 'is_satellite_provider', 'is_blocked')
            ->where('country_name', $countryName)
            ->first();
    }

    public function create(array $data)
    {
        return IpGlobal::create($data);
    }
}