<?php

namespace App\Abstracts;

abstract class ActivityAbstract
{
    // Definisikan metode statis
    abstract public static function getAccessData();
    abstract public static function create(array $data);
    abstract public static function searchAccessData(array $searchData);
}