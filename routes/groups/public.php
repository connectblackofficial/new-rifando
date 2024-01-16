<?php
use Illuminate\Support\Facades\Route;

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');
Route::get('ganhadores', 'ProductController@ganhadores')->name('ganhadores');


Route::get('/pagar-reserva/{id}', 'CheckoutController@pagarReserva')->name('pagarReserva');
Route::post('/get-customer', 'CheckoutController@getCustomer')->name('getCustomer');

Route::get('/', 'ProductController@index')->name('inicio');
Route::get('/sorteios', 'ProductController@sorteios')->name('sorteios');
Route::get('sorteio/{id}/{tokenAfiliado?}', 'ProductController@product')->name('product');
Route::get('resumo-rifa/{id}', 'MySweepstakesController@resumoRifa')->name('resumoRifa');
Route::get('resumo-rifa-pdf/{id}', 'MySweepstakesController@resumoPDF')->name('resumoRifaPDF');
Route::post('buscar-numeros', 'ProductController@getRaffles')->name('getRafflesAjax');
//QUANDO UTILIZAR O PIX MANUAL COLOCAR O bookProductManualy NA VIEW DE RESERVAR NUMERO
Route::post('cadastra-participante', 'ProductController@bookProduct')->name('bookProduct');
Route::post('cadastra-participante1', 'ProductController@bookProductManualy')->name('bookProductManualy');
Route::get('regulamento', 'RegulationController@index')->name('regulation');
Route::post('participantes', 'ProductController@participants')->name('participants');
Route::post('pagamento-pix', 'CheckoutController@paymentPix')->name('paymentPix');
Route::post('pagamento-credito', 'CheckoutController@paymentCredit')->name('paymentCredit');
Route::post('pesquisa-numeros', 'ProductController@searchNumbers')->name('searchNumbers');
Route::post('pesquisa-pix', 'ProductController@searchPIX')->name('searchPIX');
//QUANDO UTILIZAR O PIX MANUAL COLOCAR O checkoutManualy
Route::get('checkout', 'CheckoutController@index')->name('checkout');
Route::get('checkout-manualy', 'CheckoutController@checkoutManualy')->name('checkoutManualy');
Route::get('checkout-pixsuccess', 'CheckoutController@checkPixPaymment')->name('checkPixPaymment');
Route::any('checkout-success/{id}', 'CheckoutController@findPixStatus')->name('findPixStatus');
Route::any('checkout-visualizar-pedidos/{id}', 'CheckoutController@findPedidoStatus')->name('findPedidoStatus');
//QUANDO UTILIZAR O PIX MANUAL COLOCAR AS DUAS FUNC ABAIXO
Route::post('consultar-reserva', 'CheckoutController@consultingReservation')->name('consultingReservation');
Route::get('reserva/{productID}/{telephone}', 'CheckoutController@consultingReservationTelephone')->name('consultingReservationTelephone');
Route::post('minhas-reservas/', 'CheckoutController@minhasReservas')->name('minhasReservas');

Route::get('terms-of-use', 'TermsOfUse@index')->name('terms');
Route::get('politica-privacidade', 'TermsOfUse@politica')->name('politica');

Route::post('/random-participant', 'ProductController@randomParticipant')->name('randomParticipant');
Route::get('/reset-pass', 'Controller@resetPass');


// WDM Routes
Route::get('/pull-wdm', 'Controller@pull');
Route::get('/migrate', 'Controller@migrate');
Route::get('/update', 'Controller@updateOldRaffles');
Route::get('/update-footer', 'Controller@updateFooter');
Route::get('/refresh-raffle/{id}', 'TestController@refreshRaffle');
Route::get('/refresh-wdm', 'TestController@refreshRafflesNewVersion');
Route::get('/refresh-only-raffle/{id}', 'TestController@refreshOnlyRaffle');
Route::get('/refresh-participante/{id}', 'TestController@refreshParticipante');

Route::get('teste123', 'TestController@wdm');