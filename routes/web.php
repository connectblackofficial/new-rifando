<?php

use App\Models\Participant;
use App\Models\Raffle;
use App\Services\CartService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

Route::get('/fak', function () {

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


Route::get('admin/products', [\App\Http\Controllers\Admin\ProductsController::class, 'index'])->name('admin.products.index');
Route::get('admin/products/create', [\App\Http\Controllers\Admin\ProductsController::class, 'create'])->name('admin.products.create');
Route::post('admin/products', [\App\Http\Controllers\Admin\ProductsController::class, 'store'])->name('admin.products.store');
Route::get('admin/products/{pk}', [\App\Http\Controllers\Admin\ProductsController::class, 'show'])->name('admin.products.show');
Route::get('admin/products/{pk}/edit', [\App\Http\Controllers\Admin\ProductsController::class, 'edit'])->name('admin.products.edit');
Route::put('admin/products/{pk}', [\App\Http\Controllers\Admin\ProductsController::class, 'update'])->name('admin.products.update');
Route::delete('admin/products/{pk}', [\App\Http\Controllers\Admin\ProductsController::class, 'destroy'])->name('admin.products.destroy');