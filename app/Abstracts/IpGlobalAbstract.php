<?php

namespace App\Abstracts;

abstract class IpGlobalAbstract
{
    abstract public function findByNetwork($network);
    abstract public function findByCountryName($countryName);
    abstract public function create(array $data);
}