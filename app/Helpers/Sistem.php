<?php

use Illuminate\Support\Facades\File;

function getAllModelNames()
{
    $models = [];
    $path = app_path('Models');

    foreach (File::allFiles($path) as $file) {
        $models[] = pathinfo($file->getFilename(), PATHINFO_FILENAME);
    }

    return $models;
}

function api_key($service, $key_name)
{
    return \App\Models\ApiKey::where('service', $service)
        ->where('key_name', $key_name)
        ->first()
        ?->key_value;
}

if (! function_exists('envdb')) {
    function envdb($key, $default = null)
    {
        return config("app.dynamic.$key", $default);
    }
}
function formatBytes($bytes, $precision = 2)
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = $bytes > 0 ? floor(log($bytes) / log(1024)) : 0;
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);

    return round($bytes, $precision).' '.$units[$pow];
}
