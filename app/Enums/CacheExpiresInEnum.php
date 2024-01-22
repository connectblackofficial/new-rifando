<?php

namespace App\Enums;

use BenSampo\Enum\Enum;


final class CacheExpiresInEnum extends Enum
{
    const OneHour = 60;
    const OneDay = 1440;
    const OneWeek = 10080;
    const OneMonth = 43200;
    const OneYear = 525600;

    const Never = null;
}
