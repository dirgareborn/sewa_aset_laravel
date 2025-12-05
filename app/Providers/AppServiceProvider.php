<?php

namespace App\Providers;

use App\Helpers\DumpPath;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Midtrans\Config;
use Illuminate\Support\Facades\Blade;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local')) {

            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);

            $this->app->register(TelescopeServiceProvider::class);

        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('canAccess', function ($expression) {
         return "<?php if(hasAccess($expression)): ?>";
        });

        Blade::directive('endCanAccess', function () {
            return "<?php endif; ?>";
        });

        Blade::directive('fullAccess', function ($expression) {
            return "<?php if(hasFullAccess($expression)): ?>";
        });

        Blade::directive('endFullAccess', function () {
            return "<?php endif; ?>";
        });

        
        if (Schema::hasTable('api_keys')) {
            $api_keys = DB::table('api_keys')->pluck('service', 'key_name', 'key_value')->toArray();
            config(['app.dynamic' => $api_keys]);
        }
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
        config(['database.connections.mysql.dump.dump_binary_path' => DumpPath::detect()]);
    }
}
