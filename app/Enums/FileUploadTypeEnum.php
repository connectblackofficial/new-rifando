<?php

namespace App\Enums;

use App\Traits\EnumTrait;
use BenSampo\Enum\Enum;

final class FileUploadTypeEnum extends Enum
{
    use EnumTrait;

    const Document = 'document';
    const Image = 'image';

}
