<?php

define('LARAVEL_START', microtime(true));

if (file_exists($autoload = __DIR__.'/../vendor/autoload.php')) {
    require $autoload;
}

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
)->send();

$kernel->terminate($request, $response);
