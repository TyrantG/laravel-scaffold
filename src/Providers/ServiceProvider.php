<?php

namespace TyrantG\LaravelScaffold\Providers;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use TyrantG\LaravelScaffold\Listeners\ResponseHandler;
use TyrantG\LaravelScaffold\Services\AutoRegisterRoutes;

class ServiceProvider extends BaseServiceProvider
{
    protected array $routeMiddleware = [
        'laravel-scaffold.accept' => \TyrantG\LaravelScaffold\Http\Middleware\AcceptHeader::class,
        'laravel-scaffold.request-log' => \TyrantG\LaravelScaffold\Http\Middleware\RequestLogger::class,
    ];

    public function boot(): void
    {
        $this->app['events']->listen(RequestHandled::class, ResponseHandler::class);

        $configPath = __DIR__ . '/../../config/laravel-scaffold.php';
        if (function_exists('config_path')) {
            $publishPath = config_path('laravel-scaffold.php');
        } else {
            $publishPath = base_path('config/laravel-scaffold.php');
        }
        $this->publishes([$configPath => $publishPath], 'laravel-scaffold');
    }

    public function register(): void
    {
        $configPath = __DIR__ . '/../../config/laravel-scaffold.php';
        $this->mergeConfigFrom($configPath, 'laravel-scaffold');

        $this->registerLogger();
        $this->registerRouteMiddleware();
        $this->registerExceptionHandler();
        $this->registerRoutes();
    }

    protected function registerLogger(): void
    {
        $loggingConfig = Config::get('logging.channels');

        $loggingConfig['daily'] = [
            'driver' => 'daily',
            'path' => storage_path('logs/main/' . date('Y-m') . '/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 36500,
            'replace_placeholders' => true,
        ];

        $loggingConfig['request'] = [
            'driver' => 'daily',
            'path' => storage_path('logs/request/' . date('Y-m') . '/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 36500,
            'replace_placeholders' => true,
        ];

        Config::set('logging.channels', $loggingConfig);
    }

    protected function registerRouteMiddleware(): void
    {
        $router = $this->app->make('router');

        foreach ($this->routeMiddleware as $key => $middleware) {
            $router->aliasMiddleware($key, $middleware);
        }
    }

    protected function registerExceptionHandler(): void
    {
        if (config('laravel-scaffold.exception_handler.enable')) {
            $this->app->singleton(ExceptionHandler::class, config('laravel-scaffold.exception_handler.handler'));
        }
    }

    protected function registerRoutes(): void
    {
        $service = new AutoRegisterRoutes();
        $service->registerRoutes(function ($routeList) {
            foreach ($routeList as $controller) {
                if (count($controller['methods'])) {
                    $routeGroup = Route::prefix('api');
                    if (!empty($controller['middleware'])) {
                        $routeGroup->middleware($controller['middleware']);
                    }
                    $routeGroup->group(function () use ($controller) {
                        $methods = $controller['methods'];
                        foreach ($methods as $method) {
                            $apiMethods = handleApiMethod($method['method']);
                            $route = Route::match($apiMethods, $method['url'], [$controller['class'], $method['name']]);
                            if (!empty($method['middleware'])) {
                                $route->middleware($method['middleware']);
                            }
                        }
                    });
                }
            }
        });
    }
}
