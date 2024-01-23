<?php

use App\Http\Controllers\SuperAdmin\UsersController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeAdminController;
use App\Http\Controllers\ProductAdminController;
use App\Http\Controllers\MySweepstakesController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\ProductController as ProductAdmController;

Route::get('/clientes/{search?}', [HomeAdminController::class, 'clientes'])->name('clientes');
Route::get('/clientes/editar/{id}', [HomeAdminController::class, 'editarCliente'])->name('clientes.editar');
Route::put('/cliente/update/{id}', [HomeAdminController::class, 'updateCliente'])->name('clientes.update');
Route::get('dashboard', [HomeAdminController::class, 'index'])->name('home');
Route::get('adicionar-sorteio', [ProductAdminController::class, 'index'])->name('adminProduct');
Route::post('duplicar-sorteio', [ProductAdminController::class, 'duplicar'])->name('duplicarProduct');
Route::put('update/{id}', [MySweepstakesController::class, 'update'])->name('update');
Route::post('agendar-sorteio', [ProductAdminController::class, 'drawDate'])->name('drawDate');
Route::post('previsao-sorteio', [ProductAdminController::class, 'drawPrediction'])->name('drawPrediction');
Route::get('meus-sorteios', [ProductAdmController::class, 'index'])->name('mySweepstakes');
Route::any('liberar-reservas', [MySweepstakesController::class, 'releaseReservedRafflesNumbers'])->name('releaseReservedRafflesNumbers');
Route::any('pagar-reservas', [MySweepstakesController::class, 'pagarReservas'])->name('pagarReservas');
Route::any('reservar-numeros', [MySweepstakesController::class, 'reservarNumeros'])->name('reservarNumeros');
Route::get('carrega-sorteio', [MySweepstakesController::class, 'getRaffles'])->name('getRaffles');
Route::post('altera-sorteio', [MySweepstakesController::class, 'editRaffles'])->name('editRaffles');
Route::post('altera-produto', [ProductAdminController::class, 'alterProduct'])->name('alterProduct');
Route::get('perfil', [MySweepstakesController::class, 'profile'])->name('profile');
Route::post('perfil', [MySweepstakesController::class, 'updateProfile'])->name('updateProfile');
Route::post('alterar-logo', [ProductAdminController::class, 'alterarLogo'])->name('alterarLogo');
Route::post('pixel', [MySweepstakesController::class, 'pixel'])->name('pixel');
Route::post('remover-reservas', [MySweepstakesController::class, 'removeReserved'])->name('removeReserved');
Route::post('altera-status-produto', [ProductAdminController::class, 'alterStatusProduct'])->name('alterStatusProduct');
Route::post('altera-winner-produto', [ProductAdminController::class, 'alterWinnerProduct'])->name('alterWinnerProduct');
Route::post('altera-tipo-produto', [ProductAdminController::class, 'alterTypeRafflesProduct'])->name('alterTypeRafflesProduct');
Route::get('participants', [TestController::class, 'index'])->name('test');
Route::post('favoritar-produto', [ProductAdminController::class, 'favoritarRifa'])->name('favoritarRifa');
Route::patch('edit-product/{id}', [MySweepstakesController::class, 'updateProduct'])->name('updateProduct');
Route::post('add-foto-rifa', [ProductAdminController::class, 'addFoto'])->name('addFoto');
Route::post('/product/deletePhoto', [ProductAdmController::class, 'deletePhoto'])->name('excluirFoto');
Route::get('imprimir-resumo-compra/{id}', [MySweepstakesController::class, 'imprimirResumoCompra'])->name('imprimirResumoCompra');

// WDW
Route::post('/ranking-admin', [ProductController::class, 'rankingAdmin'])->name('ranking.admin');
Route::post('/definir-ganhador', [ProductController::class, 'definirGanhador'])->name('definirGanhador');
Route::post('/informar-ganhadores', [ProductController::class, 'informarGanhadores'])->name('informarGanhadores');
Route::post('/ver-ganhadores', [ProductController::class, 'verGanhadores'])->name('verGanhadores');

// WDM - Compras
Route::get('/compras/{idRifa}', [MySweepstakesController::class, 'compras'])->name('rifa.compras');
Route::put('/compras/{idRifa}', [MySweepstakesController::class, 'comprasBusca'])->name('rifa.comprasBusca');
Route::post('/liberar-todas-reservas', [MySweepstakesController::class, 'liberarTodasReservas'])->name('compras.liberarReservas');
Route::post('random-numbers', [MySweepstakesController::class, 'randomNumbers'])->name('compras.randomNumbers');
Route::post('/criar-compra', [MySweepstakesController::class, 'criarCompra'])->name('compras.criar');
Route::post('/build-modal-detalhes-compra', [MySweepstakesController::class, 'detalhesCompra'])->name('compras.detalhes');

// WDM - Whatsapp mensagens
Route::get('/wpp-mensagens', [HomeAdminController::class, 'wpp'])->name('wpp.index');
Route::post('/wpp-mensagens/salvar', [HomeAdminController::class, 'wppSalvar'])->name('wpp.salvar');

// Ganhadores
Route::get('/admin-ganhadores', [MySweepstakesController::class, 'ganhadores'])->name('painel.ganhadores');
Route::post('/add-foto-ganhador', [MySweepstakesController::class, 'addFotoGanhador'])->name('ganhador.addFoto');

// WDM - Tutoriais
Route::get('/tutoriais', [MySweepstakesController::class, 'tutoriais'])->name('tutoriais');
Route::get('/tutoriais/cadastro', [MySweepstakesController::class, 'cadastroVideos']);
Route::post('/tutoriais/cadastro', [MySweepstakesController::class, 'salvarVideo'])->name('dev.salvarVideo');
Route::get('/tutoriais/excluir-video/{id}', [MySweepstakesController::class, 'excluirVideo'])->name('dev.excluirVideo');

// WDM - Relatorios Painel Home
Route::get('/resumo-lucro', [MySweepstakesController::class, 'resumoLucro'])->name('resumo.lucro');
Route::get('/resumo-rifas-ativas', [ProductAdmController::class, 'activeProducts'])->name('resumo.rifasAtivas');
Route::get('/resumo-pendentes', [MySweepstakesController::class, 'resumoPendentes'])->name('resumo.pendentes');
Route::post('/resumo-pendentes-search', [MySweepstakesController::class, 'resumoPendentesSearc'])->name('resumo.pendentesSearch');
Route::get('/resumo-ranking', [MySweepstakesController::class, 'resumoRanking'])->name('resumo.ranking');
Route::post('/resumo-ranking/selected', [MySweepstakesController::class, 'resumoRankingSelect'])->name('resumo.rankingSelect');

Route::get('lista-afiliados', [MySweepstakesController::class, 'listaAfiliados'])->name('afiliados');
Route::get('solicitacao-pagamento', [MySweepstakesController::class, 'solicitacaoPgto'])->name('painel.solicitacaoAfiliados');
Route::get('confirmar-pgto-afiliado/{solicitacaoId}', [MySweepstakesController::class, 'confirmarPgtoAfiliado'])->name('painel.confirmarPgtoAfiliado');
Route::get('excluir-afiliado/{id}', [MySweepstakesController::class, 'excluirAfiliado'])->name('painel.excluirAfiliado');

// Send MSG API Criar Whats
Route::post('/api-wpp/send-message', [MySweepstakesController::class, 'sendMessageAPIWhats'])->name('apiWhats.sendMessage');

// WDM - Rifa Premiada
Route::get('/rifa-premiada', [MySweepstakesController::class, 'rifaPremiada'])->name('rifaPremiada');
Route::post('/selecioonar-rifa', [MySweepstakesController::class, 'getRifa'])->name('selecionarRifa');
Route::post('/buscar-cota-premiada', [MySweepstakesController::class, 'buscarCotaPremiada'])->name('buscarCotaPremiada');

Route::delete('product', [ProductAdmController::class, 'destroy'])->name('destroy');
Route::delete('product/{id}/destroy_phoyo', [ProductAdmController::class, 'destroyPhoto'])->name('product.destroy_photo');
Route::delete('product/{id}/destroy', [ProductAdmController::class, 'destroy'])->name('product.destroy');
Route::post('/product/store', [ProductAdmController::class, 'store'])->name('product.store');
Route::get('/product/create', [ProductAdmController::class, 'create'])->name('product.create');
Route::get('/product/{id}', [ProductAdmController::class, 'edit'])->name('product.edit');
Route::post('/product/{id}', [ProductAdmController::class, 'update'])->name('product.update');


Route::get('super-admin/users', [UsersController::class, 'index'])->name('super-admin.users.index');
Route::get('super-admin/users/create', [UsersController::class, 'create'])->name('super-admin.users.create');
Route::post('super-admin/users', [UsersController::class, 'store'])->name('super-admin.users.store');
Route::get('super-admin/users/{pk}', [UsersController::class, 'show'])->name('super-admin.users.show');
Route::get('super-admin/users/{pk}/edit', [UsersController::class, 'edit'])->name('super-admin.users.edit');
Route::put('super-admin/users/{pk}', [UsersController::class, 'update'])->name('super-admin.users.update');
Route::delete('super-admin/users/{pk}', [UsersController::class, 'destroy'])->name('super-admin.users.destroy');