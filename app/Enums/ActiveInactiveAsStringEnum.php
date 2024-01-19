<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ActiveInactiveAsStringEnum extends Enum
{
    const Active =   'Ativo';
    const Inactive =   'Inativo';

}
