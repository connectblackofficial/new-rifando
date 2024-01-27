<?php

use App\Exceptions\UserErrorException;
use App\Models\Site;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

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

function phoneField($name, $label = "", $currentData = [])
{
    $fieldValue = "";
    if (isset($currentData[$name])) {
        $fieldValue = $currentData[$name];
    }
    if (empty($label)) {
        $label = "Celular";
    }

    return view('crud.fields.phone-input', ['name' => $name, 'label' => $label, 'fieldValue' => $fieldValue])->render();
}

function inputField($name, $type, $currentData = [], $attrs = [])
{
    $fieldValue = "";
    if (isset($currentData[$name])) {
        $fieldValue = $currentData[$name];
    }
    return view('crud.fields.input', ['attrs' => $attrs, 'fieldValue' => $fieldValue, 'name' => $name, 'type' => $type, 'currentData' => $currentData])->render();
}

function inputText($name, $currentData = [], $attrs = [])
{
    $type = "text";
    return inputField($name, $type, $currentData, $attrs);
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
    if (env("APP_ENV") == 'local' || env("APP_ENV") == 'testing') {
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
 * @return Site
 */
function getSiteConfig(): Site
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
        'product.site.numbers' => $hasNotParams,
        'cart.destroy' => $hasNotParams,
        'site.checkout' => $hasParam,
        "getCustomer" => $hasNotParams
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

function setSiteEnv(Site $siteEnv)
{
    Session::put('siteEnv', $siteEnv);
    Session::put('siteOwnerEnv', $siteEnv->user()->firstOrFail());
}

function checkUserIdSite($userId)
{
    if ($userId != getSiteOwnerId()) {
        throw UserErrorException::pageNotFound();
    }

}
function getCountriesDdi()
{
    $callback = function () {
        $countries = [];
        foreach (\App\Models\Country::all() as $country) {
            $countries[$country->dial_code] =$country->dial_code;
        }
        return $countries;
    };
    $key = "countries_ddi";
    return getCacheOrCreate($key, null, $callback, \App\Enums\CacheExpiresInEnum::OneMonth, false);

}
function getCountries()
{
    $callback = function () {
        $countries = [];
        foreach (\App\Models\Country::all() as $country) {
            $countries[$country->dial_code] = $country['name'] . " " . $country->dial_code;
        }
        return $countries;
    };
    $key = "countries_with_ddi";
    return getCacheOrCreate($key, null, $callback, \App\Enums\CacheExpiresInEnum::OneMonth, false);

}

function validateOrFails(array $rules, array $requestData)
{

    $validator = Validator::make($requestData, $rules);
    if ($validator->fails()) {
        throw new UserErrorException($validator->errors()->first());
    }
    return $validator->validated();
}

function validarDocumento($documento)
{
    // Remove possíveis máscaras
    $documento = preg_replace('/[^0-9]/', '', $documento);

    // Verifica se é CPF ou CNPJ
    if (strlen($documento) == 11) {
        return validarCPF($documento);
    } elseif (strlen($documento) == 14) {
        return validarCNPJ($documento);
    }

    return false;
}


function validarTelefone($telefone)
{
    // Verifica se é um número de telefone internacional com '+'
    if (preg_match('/^\+[0-9]+$/', $telefone)) {
        return true;
    }

    // Verifica se é um número de telefone brasileiro (com 10 ou 11 dígitos numéricos)
    if (preg_match('/^([0-9]{10,11})$/', $telefone)) {
        return true;
    }

    return false;
}

function validarChaveAleatoriaPix($chave)
{
    // Verifica se a chave tem exatamente 32 caracteres
    if (strlen($chave) != 32) {
        return false;
    }

    // Verifica se a chave contém apenas caracteres permitidos (letras, números, caracteres especiais)
    return preg_match('/^[0-9a-zA-Z\!\@\#\$\%\^\&\*\(\)\[\]\{\}\:\;\'\"\,\<\.\>\/\?\`\~\-\_\+\=\|]+$/', $chave) === 1;
}

function validarCPF($cpf)
{
    // Verifica se o número de dígitos informados é igual a 11
    if (strlen($cpf) != 11) {
        return false;
    }

    // Verifica se nenhuma das sequências inválidas abaixo foi informada
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }

    // Calcula e confere primeiro dígito verificador
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }

    return true;
}

function validarCNPJ($cnpj)
{
    // Verifica se o número de dígitos informados é igual a 14
    if (strlen($cnpj) != 14) {
        return false;
    }

    // Verifica se a sequência é toda igual
    if (preg_match('/(\d)\1{13}/', $cnpj)) {
        return false;
    }

    // Calcula e confere primeiro dígito verificador
    for ($t = 12; $t < 14; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cnpj[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cnpj[$c] != $d) {
            return false;
        }
    }

    return true;
}

function getOnlyNumbers($string)
{
    // Remove tudo que não é número
    return preg_replace('/\D/', '', $string);
}

function removePhoneMask($phone)
{
    return getOnlyNumbers($phone);
}

function allowedDdiAsList()
{
    return implode(",", array_keys(getCountries()));
}

