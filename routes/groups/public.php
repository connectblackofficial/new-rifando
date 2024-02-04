<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MySweepstakesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegulationController;
use App\Http\Controllers\Site\CartController;
use App\Http\Controllers\Site\ProductController as ProductSiteController;
use App\Http\Controllers\TermsOfUse;
use Illuminate\Support\Facades\Route;

Route::get('home', function () {
    if (Auth::user()->isSuperAdmin()) {
        return redirect(route("super-admin.users.index"));
    }
    return redirect(route("home"));
})->name('homeRedirect');


Route::post('get-free-numbers/{id}', [ProductSiteController::class, 'getFreeNumbers'])->name('product.get-free-numbers');


Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');
Route::get('ganhadores', 'ProductsController@ganhadores')->name('ganhadores');

Route::get('/pagar-reserva/{id}', [CheckoutController::class, 'pagarReserva'])->name('pagarReserva');
Route::get('/', [ProductController::class, 'index'])->name('inicio');
Route::get('/sorteios', [ProductController::class, 'sorteios'])->name('sorteios');
Route::get('sorteio/{id}/{tokenAfiliado?}', [ProductSiteController::class, 'details'])->name('product');
Route::get('resumo-rifa/{id}', [MySweepstakesController::class, 'resumoRifa'])->name('resumoRifa');
Route::get('resumo-rifa-pdf/{id}', [MySweepstakesController::class, 'resumoPDF'])->name('resumoRifaPDF');
Route::post('buscar-numeros', [ProductController::class, 'getRaffles'])->name('getRafflesAjax');
Route::post('cadastra-participante', [ProductController::class, 'bookProduct'])->name('bookProduct');
Route::post('cadastra-participante1', [ProductController::class, 'bookProductManualy'])->name('bookProductManualy');
Route::get('regulamento', [RegulationController::class, 'index'])->name('regulation');
Route::post('participantes', [ProductController::class, 'participants'])->name('participants');
Route::post('pagamento-pix', [CheckoutController::class, 'paymentPix'])->name('paymentPix');
Route::post('pagamento-credito', [CheckoutController::class, 'paymentCredit'])->name('paymentCredit');
Route::post('pesquisa-numeros', [ProductController::class, 'searchNumbers'])->name('searchNumbers');
Route::post('pesquisa-pix', [ProductController::class, 'searchPIX'])->name('searchPIX');
Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout');

Route::get('checkout-manualy', [CheckoutController::class, 'checkoutManualy'])->name('checkoutManualy');


Route::get('checkout-pixsuccess', [CheckoutController::class, 'checkPixPaymment'])->name('checkPixPaymment');
Route::any('checkout-success/{id}', [CheckoutController::class, 'findPixStatus'])->name('findPixStatus');
Route::any('checkout-visualizar-pedidos/{id}', [CheckoutController::class, 'findPedidoStatus'])->name('findPedidoStatus');
Route::post('consultar-reserva', [CheckoutController::class, 'consultingReservation'])->name('consultingReservation');
Route::get('reserva/{productID}/{telephone}', [CheckoutController::class, 'consultingReservationTelephone'])->name('consultingReservationTelephone');
Route::post('minhas-reservas/', [CheckoutController::class, 'minhasReservas'])->name('minhasReservas');
Route::get('terms-of-use', [TermsOfUse::class, 'index'])->name('terms');
Route::get('politica-privacidade', [TermsOfUse::class, 'politica'])->name('politica');
Route::post('/random-participant', [ProductController::class, 'randomParticipant'])->name('randomParticipant');
Route::get('/reset-pass', [Controller::class, 'resetPass']);


Route::post('site/product/numbers_pg', [ProductSiteController::class, 'numbers'])->name('product.site.numbers');
Route::post('site/cart/add_rmo', [CartController::class, 'addRm'])->name('cart.add_rm');
Route::post('site/cart/resume', [CartController::class, 'index'])->name('cart.resume');
Route::delete('site/cart/destroy', [CartController::class, 'destroy'])->name('cart.destroy');


Route::get('site/customer/{uuid}/orders', [\App\Http\Controllers\Site\CustomerController::class, 'getOrders'])->name('site.customer.orders');

Route::post('site/checkout/complete', [\App\Http\Controllers\Site\CheckoutController::class, 'completeCheckout'])->name('site.checkout.complete');
Route::get('site/checkout/{uuid}', [\App\Http\Controllers\Site\CheckoutController::class, 'index'])->name('site.checkout');
Route::get('site/checkout/{uuid}/pay', [\App\Http\Controllers\Site\CheckoutController::class, 'payment'])->name('site.checkout.pay');
Route::get('site/checkout/{uuid}', [\App\Http\Controllers\Site\CheckoutController::class, 'index'])->name('site.checkout');
Route::get('site/checkout/{uuid}/1', [\App\Http\Controllers\Site\CheckoutController::class, 'step1'])->name('site.checkout.step1');
Route::post('/site/customer/get', [\App\Http\Controllers\Site\CustomerController::class, 'getCustomer'])->name('getCustomer');


Route::get('site/participant/check', [\App\Http\Controllers\Site\ParticipantsController::class, 'check'])->name('site.participant.check');
Route::post('site/participant/check', [\App\Http\Controllers\Site\ParticipantsController::class, 'processCheck'])->name('site.participant.process_check');
