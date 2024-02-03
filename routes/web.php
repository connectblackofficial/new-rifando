<?php

use App\Models\Participant;
use App\Models\Raffle;
use App\Services\CartService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

Route::get('/fak', function () {
dd(defaultDateFormat(strtotime("2024-02-28 11:21:16.000000")));
    $phoneNumberUtil = \libphonenumber\PhoneNumberUtil::getInstance();
    $phoneNumberObject = $phoneNumberUtil->parse('+5575992426909', null);
    dd($phoneNumberUtil->isValidNumber($phoneNumberObject));
    return "Aooba";

});
Route::get('/test_manualpix', function () {
    $lastParticipation = Participant::orderByDesc("id")->first();
    $lastParticipation->valor = 7.85;
    $lastParticipation->saveOrFail();
    $manual = new \App\Helpers\ManualPixGenerator(\App\Models\PixAccount::first(), $lastParticipation);
    $url = $manual->getPix();
    dd($url);
    return "<a href='$url'>" . $url . "</a>";

});

Route::get('/test_asaas', function () {
    $name = "Caique Marcelino Souza";
    $email = "caique+" . rand(1111, 9999);
    $cpf = "07559659578";
    $product = \App\Models\Product::first();
    $assasHelper = new \App\Libs\AsaasLib("5ec46e71f7f0a754ba64b3881fc75011e421eb21992d35f162d79e5d9384608c");
    $resultPricePIX = 25.99;
    $telefone = "75992426909";
    $productDesc = "Produto legal";
    $externalReferencee = rand(11111, 9999);
    $idCliente = $assasHelper->getOrCreateClienteAsaas($name, $email, $cpf, $telefone);
    dd($assasHelper->getPix($product, $idCliente, $resultPricePIX, $productDesc, $externalReferencee));
});
Route::get('/lists', function () {
    $product = \App\Models\Product::whereId(103)->first();
    event(new \App\Events\ProductUpdated($product));

});
Route::get('/numbersss', function () {
    calcExecTime('cache', function () {
        $product = \App\Models\Product::firstOrFail();
        echo json_encode($product->getCompraMaisPopularFromCache());
    });

});
Route::get('/cart_model', function () {
    $cartModell = new \App\Services\CartService(105);
    return $cartModell->getCurrentCart()->id;

});
Route::get('/session_pg', function () {
    dd(Session::all());

});
Route::middleware(['check', 'subDomain'])->group(function () {
    Route::prefix('area-afiliado')->group(function () {
        require_once base_path('routes/groups/affiliate.php');
    });
    Route::group(['middleware' => ['auth', 'isAdmin']], function () {
        require_once base_path('routes/groups/admin.php');
    });
    require_once base_path('routes/groups/public.php');


});


