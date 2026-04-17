<?php

return [
    'name' => env('APP_NAME', 'Control de Asistencia'),
    'env' => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    'timezone' => 'America/La_Paz',
    'locale' => 'es',
    'cipher' => 'AES-256-CBC',
    'key' => env('APP_KEY'),
];
