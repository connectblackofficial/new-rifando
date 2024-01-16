<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class UserRolesEnum extends Enum
{
    const Admin = 'admin';
    const User = 'user';
    const SuperAdmin = 'super admin';
}
