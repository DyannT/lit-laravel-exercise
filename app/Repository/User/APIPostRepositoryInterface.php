<?php

namespace App\Repository\User;

interface APIPostRepositoryInterface
{
    public function getEnablePost($page, $perPage);
}
