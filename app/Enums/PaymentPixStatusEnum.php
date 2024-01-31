<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PaymentPixStatusEnum extends Enum
{
    const Approved = "Aprovado";
    const Paid = "Pago";
    const Pending = "Pendente";
}
