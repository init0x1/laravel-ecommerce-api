<?php

namespace App\Policies\V1;

use App\Entities\Models\User;
use App\Permissions\V1\Abilities;

class OrderPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function store(User $user): bool
    {
        return $user->tokenCan(Abilities::CreateOrder);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $user->tokenCan(Abilities::UpdateOrder) ;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->tokenCan(Abilities::DeleteOrder) ;
    }
}
