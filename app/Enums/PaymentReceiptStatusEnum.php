<?php

namespace App\Enums;

use App\Traits\EnumTrait;
use BenSampo\Enum\Enum;


final class PaymentReceiptStatusEnum extends Enum
{
    use EnumTrait;

    const Declined = 'Recusado';
    const Approved = "Aprovado";
    const Pending = "Pendente";
}
