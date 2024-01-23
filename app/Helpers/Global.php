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

function selectField($name, $options, $currentData = [], $attrs = [])
{
    return view('crud.fields.select', ['attrs' => $attrs, 'name' => $name, 'options' => $options, 'currentData' => $currentData])->render();
}

function parseInputsAttr($name, $attrs)
{
    if (isset($attrs['class'])) {
        $attrs['class'] = " form-control " . $attrs['class'];
    } else {
        $attrs['class'] = " form-control";
    }
    if (!isset($attrs['name'])) {
        $attrs['name'] = $name;
    }
    if (!isset($attrs['id'])) {
        $attrs['id'] = $name;
    }
    $htmlAttr = "";
    foreach ($attrs as $attrName => $attrValue) {
        $htmlAttr .= " " . $attrName . '="' . $attrValue . '"';
    }
    return $htmlAttr;
}

function getInputLabelLang($name, $attrs)
{
    if (isset($attrs['base-lang']) && !empty($attrs['base-lang'])) {
        $index_lang = $attrs['base-lang'] . '_' . $name;
        return htmlLabel($index_lang);
    }
    return htmlLabel($name);

}

function getYesNoArr()
{
    return ['0' => 'no', '1' => 'yes'];
}

function inputField($name, $type, $currentData = [], $attrs = [])
{
    $fieldValue = "";
    if (isset($currentData[$name])) {
        $fieldValue = $currentData[$name];
    }
    return view('crud.fields.input', ['attrs' => $attrs, 'fieldValue' => $fieldValue, 'name' => $name, 'type' => $type, 'currentData' => $currentData])->render();
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

function safeMul($n1, $n2)
{
    return bcmul($n1, $n2, 2);
}

function safeAdd($n1, $n2)
{
    return bcadd($n1, $n2, 2);
}

function safeDiv($n1, $n2)
{
    return bcdiv($n1, $n2, 2);
}

function safeSub($n1, $n2)
{
    return bcsub($n1, $n2, 2);
}

function getCacheOrCreate($cacheKey, $instance, $callback, $timeInMinutes = 3600, $forceUpdate = false)
{
    if ($forceUpdate) {
        Cache::delete($cacheKey);
    }
    if (Cache::has($cacheKey)) {
        return Cache::get($cacheKey);
    } else {
        if (is_null($timeInMinutes)) {
            $expiresAt = null;
        } else {
            $expiresAt = now()->addMinutes($timeInMinutes);
        }
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

function isLocalEnv()
{
    if (env("APP_ENV") == 'local') {
        return true;
    }
    return false;
}

function cdnImageAsset($asset)
{

    return cdnAsset('images/' . $asset);
}

function cdnAsset($asset = "")
{
    $file = 'cdn/' . ltrim($asset, '/');;
    if (isLocalEnv() && $asset != "/" && !empty($asset)) {
        $file = $file . "?r=" . rand(1111111111, 99999999);
    }
    return asset($file);
}

function imageAsset($image)
{
    return asset('storage/' . $image);
}

function getSiteOwnerId()
{
    $siteEnv = \Session::get('siteEnv');
    return $siteEnv['user_id'];
}

function getSiteConfigId()
{
    $siteEnv = \Session::get('siteEnv');
    return $siteEnv['id'];
}

/**
 * @return User
 */
function getSiteOwnerUser()
{
    return \Session::get('siteOwnerEnv');

}


/**
 * @return Environment
 */
function getSiteConfig()
{
    return \Session::get('siteEnv');
}

function getSiteUploadPath()
{
    return 'site_' . getSiteOwnerId();
}

function convertToArray($data)
{
    return json_decode(json_encode($data), true);
}

function calcExecTime($name, $action)
{
    $start = microtime(true);
    $action();

    $end = microtime(true) - $start;
    echo "$name executed in: " . $end;

}

function routesToJs($routes)
{
    $routeTxt = "replace_id_here";
    $routesJs = [];
    foreach ($routes as $k => $id) {
        $newK = str_replace([".", '-'], "_", $k);
        if ($id) {
            $routesJs[$newK] = route($k, ['id' => $routeTxt]);
        } else {
            $routesJs[$newK] = route($k);
        }

    }
    return json_encode($routesJs);

}

function getJsRoutes()
{
    $hasNotParams = false;
    $hasParam = true;
    $routes = [
        'product.edit' => $hasParam,
        'product.destroy' => $hasParam,
        'excluirFoto' => $hasNotParams,
        'product.destroy_photo' => $hasParam,
        'verGanhadores' => $hasNotParams,
        'definirGanhador' => $hasNotParams,
        'ranking.admin' => $hasNotParams,
        'product.create' => $hasNotParams
    ];
    return routesToJs($routes);
}

function getSiteJsRoutes()
{
    $hasNotParams = false;
    $hasParam = true;
    $routes = [
        'product.get-free-numbers' => $hasParam,
        'randomParticipant' => $hasNotParams,
        'getRafflesAjax' => $hasNotParams,
        'cart.add_rm' => $hasNotParams,
        'cart.resume' => $hasNotParams,
        'product.site.numbers' => $hasNotParams

    ];
    return routesToJs($routes);
}

function isOnlyIntegers($str)
{
    return preg_match('/^-?\d+$/', $str) === 1;
}

function formatMoney($val, $showCurrency = true)
{
    if (!is_numeric($val)) {
        $val = 0;
    }
    $number = number_format($val, 2, ",", ".");
    if ($showCurrency) {
        return "R$ " . $number;
    } else {
        return $number;
    }
}

function setSiteEnv(Environment $siteEnv)
{
    Session::put('siteEnv', $siteEnv);
    Session::put('siteOwnerEnv', $siteEnv->user()->firstOrFail());
}