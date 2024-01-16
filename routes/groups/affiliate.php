<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AfiliadoController;

Route::get('/', [AfiliadoController::class, 'index'])->name('afiliado.home');
Route::get('/rifas-ativas', [AfiliadoController::class, 'rifasAtivas'])->name('afiliado.rifas');
Route::get('/cadastro', [AfiliadoController::class, 'cadastro'])->name('afiliado.cadastro');
Route::post('/novo-cadastro', [AfiliadoController::class, 'novo'])->name('afiliado.novo');
Route::post('/login', [AfiliadoController::class, 'login'])->name('afiliado.login');
Route::get('/logout', [AfiliadoController::class, 'logout'])->name('afiliado.logout');

Route::group(['middleware' => ['auth', 'isAfiliado']], function () {
    Route::get('/pagamentos', [AfiliadoController::class, 'pagamentos'])->name('afiliado.pagamentos');
    Route::get('/afiliar-se/{idRifa}', [AfiliadoController::class, 'afiliar'])->name('afiliado.afiliarSe');
    Route::get('/solicitar-saque', [AfiliadoController::class, 'solicitarSaque'])->name('afiliado.solicitarSaque');
});