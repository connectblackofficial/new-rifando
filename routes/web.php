<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['check', 'subDomain'])->group(function () {
    Route::prefix('area-afiliado')->group(function () {
        require_once base_path('routes/groups/affiliate.php');
    });
    Route::group(['middleware' => ['auth', 'isAdmin']], function () {
        require_once base_path('routes/groups/admin.php');
    });
    Route::group(['middleware' => ['auth', 'isSuperAdmin']], function () {
        require_once base_path('routes/groups/super-admin.php');
    });
    require_once base_path('routes/groups/public.php');
});

