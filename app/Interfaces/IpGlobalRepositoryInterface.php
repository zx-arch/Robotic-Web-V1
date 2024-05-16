<?php

namespace App\Interfaces;

interface IpGlobalRepositoryInterface
{
    public function findByNetwork($network);
    public function findByCountryName($countryName);
    public function create(array $data);
}