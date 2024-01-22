<?php

namespace App\Traits;

use App\Enums\CacheExpiresInEnum;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

trait HasEloquentCacheTrait
{
    public function __call($method, $parameters)
    {

        $chunks = explode('_', Str::snake($method));
        $chunksQty = count($chunks);
        $lastIndex = $chunksQty - 1;
        $semiLastIndex = $chunksQty - 2;
        if (isset($chunks[$lastIndex]) && $chunks[$lastIndex] == 'cache' && isset($chunks[$semiLastIndex]) && $chunks[$semiLastIndex] == 'from' && $chunksQty >= 3) {
            return $this->createCache($method, $parameters);
        }
        return is_callable(['parent', '__call']) ? parent::__call($method, $parameters) : null;
    }


    public static function getTableName()
    {
        return (new self())->table;
    }

    private function createCache($method, $parameters)
    {
        $instance = $this;
        if (isset($parameters[0]) && is_bool($parameters[0])) {
            $forceRecreate = $parameters[0];
        } else {
            $forceRecreate = false;
        }
        if (isset($parameters[1]) && is_int($parameters[1]) && $parameters[1] > 0) {
            $expiresIn = $parameters[1];
        } else {
            $expiresIn = CacheExpiresInEnum::Never;
        }
        $methodName = lcfirst(str_replace(['FromCache'], '', $method));
        $key = $this->getTable() . "_" . $methodName . "_" . $this->id;
        return getCacheOrCreate($key, $instance, function ($instance) use ($methodName) {
            return $instance->$methodName();
        }, $expiresIn, $forceRecreate);
    }
}
