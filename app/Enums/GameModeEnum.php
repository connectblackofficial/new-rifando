<?php

namespace App\Enums;

use App\Traits\EnumTrait;
use BenSampo\Enum\Enum;
final class GameModeEnum extends Enum
{
    use EnumTrait;

    const Numbers =   'numeros';
    const CompleteFarm =   'fazendinha-completa';
    const HalfFarm = "fazendinha-meio";
}
