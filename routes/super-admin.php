<?php

use App\Http\Controllers\Controller;

Route::get('user/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

Route::get('/pull-wdm', [Controller::class, 'pull']);
Route::get('/migrate', [Controller::class, 'migrate']);
Route::get('/update', [Controller::class, 'updateOldRaffles']);
Route::get('/update-footer', [Controller::class, 'updateFooter']);
