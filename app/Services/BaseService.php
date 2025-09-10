<?php

namespace App\Services;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BaseService
{
    use AuthorizesRequests;

    protected $policyClass;

    public function isAble($ability, $targetModel)
    {
        try {
            $this->authorize($ability, [$targetModel, $this->policyClass]);

            return true;
        } catch (AuthorizationException $ex) {
            return false;
        }
    }
}
