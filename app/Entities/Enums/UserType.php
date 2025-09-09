<?php

namespace App\Entities\Enums;

enum UserType: string
{
    case ADMIN = 'admin';
    case SELLER = 'seller';
    case CUSTOMER = 'customer';
}
