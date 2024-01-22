<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BadMethodCallException;


final class CacheKeysEnum extends Enum
{
    const ComprasAuto = 'p_compras_auto_{id}';
    const OptionTwo = 1;
    const OptionThree = 2;

    public static function __callStatic($name, $arguments)
    {
        if (isset($arguments[0]) && is_integer($arguments[0])) {
            $id = $arguments[0];
            foreach (self::toArray() as $key => $val) {
                $methodNameCache = "get" . $key . "CacheKey";
                if ($name == $methodNameCache) {
                    return self::getCacheKey($val, $id);
                }
            }
        }
        throw new BadMethodCallException("Method '$name' does not exist in class: " . __CLASS__);

    }


    private static function getCacheKey($value, $id)
    {
        return self::replaceId($value, $id);
    }

    private static function replaceId($key, $id)
    {
        return str_replace("{id}", $id, $key);
    }

}
