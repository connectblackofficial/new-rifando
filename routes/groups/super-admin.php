<?php

use App\Http\Controllers\SuperAdmin\SitesController;
use App\Http\Controllers\SuperAdmin\UsersController;

Route::get('super-admin/users', [UsersController::class, 'index'])->name('super-admin.users.index');
Route::get('super-admin/users/create', [UsersController::class, 'create'])->name('super-admin.users.create');
Route::post('super-admin/users', [UsersController::class, 'store'])->name('super-admin.users.store');
Route::get('super-admin/users/{pk}', [UsersController::class, 'show'])->name('super-admin.users.show');
Route::get('super-admin/users/{pk}/edit', [UsersController::class, 'edit'])->name('super-admin.users.edit');
Route::put('super-admin/users/{pk}', [UsersController::class, 'update'])->name('super-admin.users.update');
Route::delete('super-admin/users/{pk}', [UsersController::class, 'destroy'])->name('super-admin.users.destroy');

Route::get('super-admin/sites', [SitesController::class, 'index'])->name('super-admin.sites.index');
Route::get('super-admin/sites/create', [SitesController::class, 'create'])->name('super-admin.sites.create');
Route::post('super-admin/sites', [SitesController::class, 'store'])->name('super-admin.sites.store');
Route::get('super-admin/sites/{pk}', [SitesController::class, 'show'])->name('super-admin.sites.show');
Route::get('super-admin/sites/{pk}/edit', [SitesController::class, 'edit'])->name('super-admin.sites.edit');
Route::put('super-admin/sites/{pk}', [SitesController::class, 'update'])->name('super-admin.sites.update');
Route::delete('super-admin/sites/{pk}', [SitesController::class, 'destroy'])->name('super-admin.sites.destroy');