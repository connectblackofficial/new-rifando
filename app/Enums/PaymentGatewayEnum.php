<?php

namespace App\Enums;

use App\Traits\EnumTrait;
use BenSampo\Enum\Enum;

final class PaymentGatewayEnum extends Enum
{
    use EnumTrait;

    const MP = "mp";
    const ASAAS = "asaas";
   // const PAGGUE = "paggue";

    const MANUAL_PIX = "pix";
}
