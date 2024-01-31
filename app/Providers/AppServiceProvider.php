<?php

namespace App\Providers;

use App\Models\ConsultingEnviroment;
use App\Models\Product;
use App\Models\Promo;
use App\Models\User;
use App\Observers\ProductObserver;
use App\Observers\PromoObserver;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Guard $auth)
    {
        Schema::defaultStringLength(191);
        Carbon::setLocale('pt_BR');

        Blade::directive('lang', function ($expression) {
            return "<?php echo __($expression); ?>";
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
