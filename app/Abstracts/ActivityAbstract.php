<?php

namespace App\Abstracts;

use Illuminate\Database\Eloquent\Builder;


abstract class ActivityAbstract
{
    // Definisikan metode statis
    abstract public static function getActivityInfo($session = null);
    abstract public static function getAccessData();
    abstract public static function create(array $data);
    abstract public static function searchAccessData(array $searchData);
    abstract public static function customQuery(Builder $query): self;
    abstract public static function setPaginate(int $perPage = 15);
}