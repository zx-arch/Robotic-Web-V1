<?php

namespace App\Interfaces;

interface ActivityRepositoryInterface
{
    public function getActivityInfo($ipAddress, $userAgent);
}