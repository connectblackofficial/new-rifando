<?php

use Illuminate\Support\Facades\Route;

Route::get('/pwd', function () {
    return Hash::make("123456");
});

Route::middleware(['check'])->group(function () {

    Route::prefix('area-afiliado')->group(function () {
        require_once base_path('routes/groups/affiliate.php');
    });

    Route::group(['middleware' => ['auth', 'isAdmin']], function () {
        require_once base_path('routes/groups/admin.php');
    });

    require_once base_path('routes/groups/public.php');

});


Route::get('super-admin/users', [\App\Http\Controllers\SuperAdmin\UsersController::class, 'index'])->name('super-admin.users.index');
Route::get('super-admin/users/create', [\App\Http\Controllers\SuperAdmin\UsersController::class, 'create'])->name('super-admin.users.create');
Route::post('super-admin/users', [\App\Http\Controllers\SuperAdmin\UsersController::class, 'store'])->name('super-admin.users.store');
Route::get('super-admin/users/{pk}', [\App\Http\Controllers\SuperAdmin\UsersController::class, 'show'])->name('super-admin.users.show');
Route::get('super-admin/users/{pk}/edit', [\App\Http\Controllers\SuperAdmin\UsersController::class, 'edit'])->name('super-admin.users.edit');
Route::put('super-admin/users/{pk}', [\App\Http\Controllers\SuperAdmin\UsersController::class, 'update'])->name('super-admin.users.update');
Route::delete('super-admin/users/{pk}', [\App\Http\Controllers\SuperAdmin\UsersController::class, 'destroy'])->name('super-admin.users.destroy');