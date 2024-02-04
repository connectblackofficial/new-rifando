<?php

use App\Http\Controllers\SuperAdmin\SitesController;
use App\Models\Participant;
use App\Models\Raffle;
use App\Services\CartService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;


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

