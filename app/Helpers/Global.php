<?php

use App\Environment;
use App\Exceptions\UserErrorException;
use App\Models\User;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;

function getCrudData($crudName, $viewName, $routeGroup)
{
    $routeGroup = str_replace("/", "", $routeGroup);
    return [
        'routeEdit' => "{$routeGroup}.{$viewName}.edit",
        'routeIndex' => "{$routeGroup}.{$viewName}.index",
        'routeDelete' => "{$routeGroup}.{$viewName}.destroy",
        'routeShow' => "{$routeGroup}.{$viewName}.show",
        "routeView" => "{$routeGroup}.{$viewName}.show",
        "routeCreate" => "{$routeGroup}.{$viewName}.create",
        "routeStore" => "{$routeGroup}.{$viewName}.store",
        'routeUpdate' => "{$routeGroup}.{$viewName}.update",
        "indexTitle" => __("{$viewName}.index")
    ];

}

function selectField($name, $options, $currentData = [])
{
    return view('crud.fields.select', ['name' => $name, 'options' => $options, 'currentData' => $currentData])->render();
}

function inputField($name, $type, $currentData = [])
{
    $fieldValue = "";
    if (isset($currentData[$name])) {
        $fieldValue = $currentData[$name];
    }
    return view('crud.fields.input', ['fieldValue' => $fieldValue, 'name' => $name, 'type' => $type, 'currentData' => $currentData])->render();
}

function itemRowView($formatFieldsFn, $item, $index)
{

    if (isset($formatFieldsFn[$index])) {
        $fn = $formatFieldsFn[$index];

        if (is_callable($fn)) {
            return $fn($item->{$index});
        }
        return '';
    } else {
        return $item->{$index};
    }

}

function createSlug($string)
{

    $table = array(
        'Š' => 'S', 'š' => 's', 'Đ' => 'Dj', 'đ' => 'dj', 'Ž' => 'Z', 'ž' => 'z', 'Č' => 'C', 'č' => 'c', 'Ć' => 'C', 'ć' => 'c',
        'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
        'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O',
        'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss',
        'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e',
        'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o',
        'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'ý' => 'y', 'þ' => 'b',
        'ÿ' => 'y', 'Ŕ' => 'R', 'ŕ' => 'r', '/' => '-', ' ' => '-'
    );

    // -- Remove duplicated spaces
    $stripped = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $string);


    // -- Returns the slug
    return strtolower(strtr($string, $table));
}

function htmlLabel($txt)
{
    return ucfirst(strtolower(__($txt)));
}

function htmlTitle($txt)
{
    return ucwords(strtolower(__($txt)));
}

function getCacheOrCreate($cacheKey, $instance, $callback, $timeInMinutes = 3600, $forceUpdate = false)
{
    if ($forceUpdate) {
        Cache::delete($cacheKey);
    }
    if (Cache::has($cacheKey)) {
        return Cache::get($cacheKey);
    } else {
        $expiresAt = now()->addMinutes($timeInMinutes);
        Cache::put($cacheKey, $callback($instance), $expiresAt);
        return Cache::get($cacheKey);
    }
}

function parseExceptionMessage(\Exception $e)
{
    $debugMessage = "{$e->getMessage()}:{$e->getFile()}:{$e->getLine()}";
    if (env("APP_ENV") == 'local') {
        return $debugMessage;
    }
    if ($e instanceof UserErrorException) {
        return $e->getMessage();
    } else {
        return __('an unknown error has occurred.');
    }
}

function imageAsset($image)
{
    return asset('storage/' . $image);
}

function getSiteOwner()
{
    $siteEnv = \Session::get('siteEnv');
    return $siteEnv['user_id'];
}

function getSiteConfigId()
{
    $siteEnv = \Session::get('siteEnv');
    return $siteEnv['id'];
}

function getSiteOwnerUser()
{
    return User::whereId(getSiteOwner())->first();
}


function getSiteConfig()
{
    return \Session::get('siteEnv');
}

function getSiteUploadPath()
{
    return 'site_' . getSiteOwner();
}