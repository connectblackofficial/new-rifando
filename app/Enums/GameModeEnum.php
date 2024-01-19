<?php

namespace App\Enums;

use App\Traits\EnumTrait;
use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class GameModeEnum extends Enum
{
    use EnumTrait;

    const Yes =   'numeros';
    const CompleteFarm =   'fazendinha-completa';
    const HalfFarm = "fazendinha-meio";
}
