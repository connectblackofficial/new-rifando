<?php

namespace App\Traits;

trait EnumTrait
{
    public static function getRule()
    {
        return 'in:' . implode(",", self::getValues());
    }

    public static function getValuesAsSelect(): array
    {
        $values = [];
        foreach (self::getValues() as $v) {
            $values[$v] = $v;
        }
        return $values;
    }
}