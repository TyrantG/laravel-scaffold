<?php

namespace TyrantG\LaravelScaffold\Providers;

use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use TyrantG\LaravelScaffold\Listeners\ResponseHandler;

class ServiceProvider extends BaseServiceProvider
{
    public function boot(): void
    {
        $this->app['events']->listen(RequestHandled::class, ResponseHandler::class);

        $configPath = __DIR__.'/../../config/laravel-scaffold.php';
        if (function_exists('config_path')) {
            $publishPath = config_path('laravel-scaffold.php');
        } else {
            $publishPath = base_path('config/laravel-scaffold.php');
        }
        $this->publishes([$configPath => $publishPath], 'config');

        $this->registerLogger();
    }

    public function register(): void
    {
        $configPath = __DIR__.'/../../config/laravel-scaffold.php';
        $this->mergeConfigFrom($configPath, 'laravel-scaffold');
    }

    protected function registerLogger(): void
    {
        $loggingConfig = Config::get('logging.channels');

        $loggingConfig['daily'] = [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 36500,
            'replace_placeholders' => true,
        ];

        $loggingConfig['request'] = [
            'driver' => 'daily',
            'path' => storage_path('logs/request.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 36500,
            'replace_placeholders' => true,
        ];

        Config::set('logging.channels', $loggingConfig);
    }
}
