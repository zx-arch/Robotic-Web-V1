<?php

namespace App\Services;

use App\Repositories\IpGlobalRepository;

class IpGlobalService
{
    protected $repository;

    public function __construct(IpGlobalRepository $repository)
    {
        $this->repository = $repository;
    }

    public function recordIfNotExists($ip)
    {
        $existingIP = $this->repository->findByNetwork($ip);

        if (!$existingIP && session()->has('myActivity.ip_address')) {
            $countryName = session('myActivity.country');
            $countryInfo = $this->repository->findByCountryName($countryName);

            $this->repository->create([
                'network' => session('myActivity.ip_address'),
                'geoname_id' => $countryInfo->geoname_id ?? '0',
                'continent_code' => $countryInfo->continent_code ?? '',
                'continent_name' => $countryInfo->continent_name ?? '',
                'country_iso_code' => $countryInfo->country_iso_code ?? '',
                'country_name' => $countryName,
                'is_anonymous_proxy' => $countryInfo->is_anonymous_proxy ?? false,
                'is_satellite_provider' => $countryInfo->is_satellite_provider ?? false,
                'is_blocked' => $countryInfo->is_blocked ?? false,
                'netmask' => \App\Models\IpGlobal::calculateNetmask($ip),
            ]);
        }
    }
}