<?php

namespace App\Interfaces;

interface ActivityRepositoryInterface
{
    public function create(array $data);
    public function getAccessData();
    public function searchAccessData(array $searchData);
}