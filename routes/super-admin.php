<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\TestController;

Route::get('user/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

Route::get('/pull-wdm', [Controller::class, 'pull']);
Route::get('/migrate', [Controller::class, 'migrate']);
Route::get('/update', [Controller::class, 'updateOldRaffles']);
Route::get('/update-footer', [Controller::class, 'updateFooter']);
Route::get('/refresh-raffle/{id}', [TestController::class, 'refreshRaffle']);
Route::get('/refresh-wdm', [TestController::class, 'refreshRafflesNewVersion']);
Route::get('/refresh-only-raffle/{id}', [TestController::class, 'refreshOnlyRaffle']);
Route::get('/refresh-participante/{id}', [TestController::class, 'refreshParticipante']);*