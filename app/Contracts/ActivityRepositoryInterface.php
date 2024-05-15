<?php

namespace App\Contracts;

interface ActivityRepositoryInterface
{
    public function getActivityInfo($ipAddress, $userAgent);
}