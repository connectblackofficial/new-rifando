<?php

namespace App\Traits;

trait EnumTrait
{
    public static function getRule()
    {
        return 'in:' . self::valuesSeparatedByCommas();
    }

    public static function valuesSeparatedByCommas()
    {
        return implode(",", self::getValues());
    }

    public static function getValuesAsSelect(): array
    {
        $values = [];
        foreach (self::getValues() as $v) {
            $values[$v] = strtolower($v);
        }
        return $values;
    }

    public static function getValueAsSelectedNew(): array
    {
        $values = [];
        foreach (self::toSelectArray() as $k => $v) {

            $values[$k] = strtolower($v);
        }
        return $values;
    }

    public static function getSelectCrudFormat(): array
    {
        $arr = self::toSelectArray();
        $newArr = [];
        foreach ($arr as $k => $v) {
            $newK=(string)$k;
            $newArr[$newK] = strtolower($v);
        }

        return $newArr;
    }
}