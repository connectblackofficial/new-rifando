<?php

namespace App\Enums;

use App\Traits\EnumTrait;
use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class YesNoAsIntEnum extends Enum
{
    use EnumTrait;

    const No = '0';
    const Yes = '1';


}
