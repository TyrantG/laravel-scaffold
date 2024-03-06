<?php

namespace TyrantG\LaravelScaffold\Services;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class ParseAnnotation
{
    public function __construct()
    {

    }

    /**
     * @throws ReflectionException
     */
    public function parseController(string $class): array
    {
        $controllerInfo = [];
        $reflectionClass = new ReflectionClass($class);

        $controllerInfo['className'] = $this->getControllerName($reflectionClass->getName());
        $controllerInfo['controllerName'] = $this->getControllerName($reflectionClass->getName(), true);
        $controllerInfo['class'] = $reflectionClass->getName();
        $controllerInfo['attributes'] = $this->parseAttributes($reflectionClass->getAttributes());
        $controllerInfo['methods'] = $this->parseMethods($reflectionClass->getMethods());

        return $controllerInfo;
    }

    /**
     * @param  array<int, ReflectionMethod>  $methods
     */
    protected function parseMethods(array $methods): array
    {
        $methodList = [];
        foreach ($methods as $method) {
            if ($method instanceof ReflectionMethod) {
                $name = $method->getName();
                $attributes = $method->getAttributes();

                if (count($attributes)) {
                    $methodList[] = [
                        'name' => $name,
                        ...$this->parseAttributes($attributes),
                    ];
                }
            }
        }

        return $methodList;
    }

    /**
     * @param  array<int, ReflectionAttribute>  $attributes
     */
    protected function parseAttributes(array $attributes): array
    {
        $attrs = [];
        foreach ($attributes as $attribute) {
            $value = '';
            if ($attribute instanceof ReflectionAttribute) {
                $name = $this->getClassName($attribute->getName());
                $params = $attribute->getArguments();
                if (! empty($params)) {
                    if (! empty($params[0]) && is_string($params[0]) && count($params) === 1) {
                        $value = $params[0];
                    } else {
                        if (! empty($params[0])) {
                            $paramObj = [];
                            foreach ($params as $k => $value) {
                                $key = $k === 0 ? 'name' : $k;
                                $paramObj[$key] = $value;
                            }
                        } else {
                            $paramObj = $params;
                        }
                        $value = $paramObj;
                    }
                }
            } else {
                $name = $this->getClassName(get_class($attribute));
                $valueObj = objectToArray($attribute);
                if (array_key_exists('name', $valueObj) && count($valueObj) === 1) {
                    $value = $valueObj['name'] === null ? true : $valueObj['name'];
                } else {
                    $value = $valueObj;
                }
            }

            if (! empty($attrs[$name]) && is_array($attrs[$name]) && array_key_first($attrs[$name]) === 0) {
                $attrs[$name][] = $value;
            } elseif (! empty($attrs[$name])) {
                $attrs[$name] = [$attrs[$name], $value];
            } else {
                $attrs[$name] = $value;
            }
        }

        return $attrs;
    }

    protected function getClassName(string $path): string
    {
        $pathList = explode('\\', $path);

        return lcfirst($pathList[count($pathList) - 1]);
    }

    protected function getControllerName(string $controller, bool $replace = false): string
    {
        $pathList = explode('\\', $controller);
        $controller = $pathList[count($pathList) - 1];

        return $replace ? str_replace('Controller', '', $controller) ?: 'Index' : $controller;
    }
}
