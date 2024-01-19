<?php

use App\Models\Participant;
use Illuminate\Support\Facades\Route;

Route::get('/pwd', function () {
   dd(getSiteConfig()->user()->first());
});

Route::middleware(['check', 'subDomain'])->group(function () {

    Route::prefix('area-afiliado')->group(function () {
        require_once base_path('routes/groups/affiliate.php');
    });

    Route::group(['middleware' => ['auth', 'isAdmin']], function () {
        require_once base_path('routes/groups/admin.php');
    });

    require_once base_path('routes/groups/public.php');


});


