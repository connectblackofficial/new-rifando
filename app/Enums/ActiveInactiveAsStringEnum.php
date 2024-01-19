<?php

namespace App\Enums;

use App\Traits\EnumTrait;
use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ActiveInactiveAsStringEnum extends Enum
{
    use EnumTrait;
    const Active =   'Ativo';
    const Inactive =   'Inativo';

}
