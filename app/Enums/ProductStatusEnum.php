<?php

namespace App\Enums;

use App\Traits\EnumTrait;
use BenSampo\Enum\Enum;

final class ProductStatusEnum extends Enum
{
    use EnumTrait;

    const Scheduled = "Agendado";
    const Finished = "Finalizado";
    const Active = "Ativo";
    const Inactive = "Inativo";

}
