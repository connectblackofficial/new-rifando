<?php

namespace App\Enums;

use App\Traits\EnumTrait;
use BenSampo\Enum\Enum;

final class UserRolesEnum extends Enum
{
    use EnumTrait;

    const Admin = 'admin';
    const User = 'user';
    const SuperAdmin = 'super admin';
}
