<?php
function checkAutoTrans($key, $autoCreate = true)
{
    $env = env('APP_ENV');
    if (!is_null($key) && $env != 'production' && $autoCreate && !str_contains($key, '.')) {
        $fileDir = resource_path("lang/pt-BR.json");
        $json = file_get_contents($fileDir);
        $transArr = json_decode($json, true);
        if (!isset($transArr[$key])) {
            $transArr[$key] = $key;
            $newKey = str_replace("+", "", $key);
            if (is_numeric($newKey)) {
                return false;
            }
            file_put_contents($fileDir, json_encode($transArr));
        }
    }
}

function __($key, $replace = [], $locale = null)
{

    checkAutoTrans($key);
    return app('translator')->getFromJson($key, $replace, $locale);
}

function trans($key = null, $replace = [], $locale = null, $autoCreate = true)
{
    if (is_null($key)) {
        return app('translator');
    }
    checkAutoTrans($key);
    return app('translator')->get($key, $replace, $locale);
}


