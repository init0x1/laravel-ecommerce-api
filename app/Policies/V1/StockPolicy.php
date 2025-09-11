<?php

namespace App\Policies\V1;

use App\Entities\Models\User;
use App\Permissions\V1\Abilities;

class StockPolicy
{

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $user->tokenCan(Abilities::UpdateStock);
    }

}
