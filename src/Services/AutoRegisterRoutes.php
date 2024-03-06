<?php

namespace TyrantG\LaravelScaffold\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AutoRegisterRoutes
{
    private array $config;

    private array $classList = [];
    private array $controllerInfoList = [];

    public function __construct()
    {
        $this->getConfig();
        $this->readControllers();
        $this->parseControllers();
    }

    private function getConfig(): void
    {
        $this->config = config('laravel-scaffold.api');
    }

    private function readControllers(): void
    {
        $paths = $this->config['paths'] ?? [];

        if (count($paths)) {
            foreach ($paths as $path) {
                $base_path = base_path($path);
                $path_namespace = implode('\\', array_map('ucfirst', explode('/', $path)));

                if (File::exists($base_path)) {
                    $controllers = File::allFiles($base_path);
                    foreach ($controllers as $controller) {
                        $relativePathname = $controller->getRelativePathname();
                        $relativeClassname = str_replace('.php', '', $relativePathname);
                        $classname = $path_namespace.'\\'.$relativeClassname;
                        $this->classList[] = compact('classname', 'relativeClassname');
                    }
                }
            }
        }
    }

    private function parseControllers(): void
    {
        $classList = $this->classList;
        $parser = new ParseAnnotation();

        foreach ($classList as $class) {
            $parseResult = $parser->parseController($class['classname']);
            $resultAttributes = $parseResult['attributes'];
            if (! isset($resultAttributes['notParse'])) {
                if (count($parseResult['methods']) > 0) {
                    $this->controllerInfoList[] = $this->autoGenerateRoute($parseResult, $class['relativeClassname']);
                }
            }
        }
    }

    public function registerRoutes(callable $cb): void
    {
        $autoRegisterApis = [];
        $cb($this->controllerInfoList);
    }

    protected function autoGenerateRoute(array $parseResult, string $relativeClassname): array
    {
        $apiPrefix = implode('/', array_map('Str::snake', explode('/', str_replace("\\", "/", substr($relativeClassname, 0, strrpos($relativeClassname, "\\"))))));

        foreach ($parseResult['methods'] as &$method) {
            if (! isset($method['method'])) {
                $method['method'] = 'GET';
            }
            if (! isset($method['url'])) {
                $method['url'] = $apiPrefix . '/' . $method['name'];
            }
        }

        return $parseResult;
    }
}
