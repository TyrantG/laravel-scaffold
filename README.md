# laravel-scaffold

Laravel业务基础框架

## 安装

1. ```shell
    composer require tyrantg/laravel-scaffold
   ```
2. 发布配置文件
   ```shell
   php artisan vendor:publish --tag="laravel-scaffold"
   ```
3. 注册中间件
   ```php
   // app/Http/Kernel.php
   protected $middlewareGroups = [
       // ...
       'api' => [
           // ...
           'laravel-scaffold.accept:json',
           'laravel-scaffold.request-log',
       ],
   ];
   ```
