<?php

namespace App\Traits;

trait EnumTrait
{
    public static function getRule()
    {
        return 'in:' . implode(",", self::getValues());
    }
}