<?php

namespace App\Providers;

use App\Models\ConsultingEnviroment;
use App\Models\User;
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

        view()->composer('*', function ($view) use ($auth) {
            $social = DB::table('sites')->where('id', 2)->first();
            $user = User::find(23);
            $view->with('data', [
                'social' => @$social,
                'user' => @$user
            ]);
        });
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
