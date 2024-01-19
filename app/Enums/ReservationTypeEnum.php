<?php

namespace App\Enums;

use App\Traits\EnumTrait;
use BenSampo\Enum\Enum;

final class ReservationTypeEnum extends Enum
{
    use EnumTrait;

    const Manual =   'manual';
    const Automatic =   'automatico';
    const Merged = 'mesclado';
}
