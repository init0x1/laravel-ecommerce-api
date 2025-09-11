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

    //  Product abilities
    public const CreateProduct = 'product:create';

    public const UpdateProduct = 'product:update';

    public const DeleteProduct = 'product:delete';

    // Order abilities
    public const CreateOrder = 'order:create';

    public const UpdateOrder = 'order:update';

    public const DeleteOrder = 'order:delete';

    // Stock abilities
    public const UpdateStock = 'stock:update';

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

                // Product abilities - Admin manages products
                self::CreateProduct,
                self::UpdateProduct,
                self::DeleteProduct,

                // Order abilities - Admin manages orders
                self::UpdateOrder,
                self::DeleteOrder,

                // Stock abilities - Admin manages stock
                self::UpdateStock,
            ],

            UserType::SELLER => [
                // Product abilities - Seller manages products
                self::CreateProduct,
                self::UpdateProduct,
                self::DeleteProduct,

                // Order abilities - Seller manages orders
                self::UpdateOrder,
                self::DeleteOrder,

                // Stock abilities - Seller manages stock
                self::UpdateStock,
            ],

            UserType::CUSTOMER => [

                // Order abilites
                self::CreateOrder
            ],
        };
    }
}
