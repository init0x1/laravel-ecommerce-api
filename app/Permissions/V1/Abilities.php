<?php

namespace App\Permissions\V1;

use App\Entities\Enums\UserType;
use App\Entities\Models\User;

final class Abilities
{
    // Category abilities
    public const CreateCategory = 'category:create';

    public const UpdateCategory = 'category:update';

    public const DeleteCategory = 'category:delete';

    // User abilities
    public const CreateUser = 'user:create';

    public const UpdateUser = 'user:update';

    public const DeleteUser = 'user:delete';

    public const ViewAllUsers = 'user:view:all';

    /**
     * Get all abilities for a specific user based on their role
     */
    public static function getAbilities(User $user): array
    {
        return match ($user->role) {
            UserType::ADMIN => [

                // Category abilities - Admin manages categories
                self::CreateCategory,
                self::UpdateCategory,
                self::DeleteCategory,

                // User abilities - Admin manages users
                self::CreateUser,
                self::UpdateUser,
                self::DeleteUser,
                self::ViewAllUsers,
            ],

            UserType::SELLER => [

            ],

            UserType::CUSTOMER => [

            ],
        };
    }
}
