<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BadMethodCallException;


final class CacheKeysEnum extends Enum
{
    const PaginationKeyFomNumbers = 'p_number_page_{id}_{pg}';
    const  PaginationQtyPagesFomNumbers = 'p_number_qty_{id}';

    const  PaginationQtyRowsperPage = 'p_number_row_per_page_{id}';

    const  CartProductKey = 'cart_product_{id}';
    const  SiteFaqsKey = 'site_faqs_{id}';

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


    static function replaceVars($value, $vars = [])
    {
        foreach ($vars as $k => $v) {
            $value = str_replace('{' . $k . '}', $v, $value);
        }

        return $value;
    }

    public static function getQtyPaginationPageKey($productId)
    {
        $key = self::PaginationQtyPagesFomNumbers;
        return self::replaceVars($key, ['id' => $productId]);
    }

    public static function getPaginationPageKey($productId, $page)
    {
        $key = self::PaginationKeyFomNumbers;
        return self::replaceVars($key, ['id' => $productId, 'pg' => $page]);
    }

    public static function getQtyQtyRowsPerPageKey($productId)
    {
        $key = self::PaginationQtyRowsperPage;
        return self::replaceVars($key, ['id' => $productId]);
    }

    public static function getCartSessionKey($productId)
    {
        $key = self::CartProductKey;
        return self::replaceVars($key, ['id' => $productId]);
    }

    public static function getSIteFaqKey($userId)
    {
        $key = self::SiteFaqsKey;
        return self::replaceVars($key, ['id' => $userId]);
    }
}
