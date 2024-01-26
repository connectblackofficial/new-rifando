<?php

namespace App\Enums;

use BenSampo\Enum\Enum;


final class PixKeyTypeEnum extends Enum
{
    const Email = "email";
    const CPF = "cpf";
    const CNPJ = "cnpj";
    const Phone = "phone";
    const Random = "random";
}
