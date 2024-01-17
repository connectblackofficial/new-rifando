<?php

use App\Models\Participant;
use Illuminate\Support\Facades\Route;

Route::get('/pwd', function () {
    dd(Participant::inRandomOrder()->select('name')->first());
    $tables = \Illuminate\Support\Facades\DB::select('SHOW TABLES');
    $tables = array_map('current', $tables);
    $list = [];
    foreach ($tables as $table) {
        if (Schema::hasColumn($table, 'user_id')) {

            DB::table($table)->update(["user_id" => 1]);
        }

    }
    return json_encode($list);
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


