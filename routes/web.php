<?php

use App\Models\Participant;
use App\Models\Raffle;
use App\Services\CartService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

Route::get('/lista_product', function () {
    $faker = Faker\Factory::create();
    $productName = $faker->word;
    echo $productName;

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


