<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Maintenance mode check (safe even if file missing)
if (file_exists(__DIR__.'/storage/framework/maintenance.php')) {
    require __DIR__.'/storage/framework/maintenance.php';
}

// Load Composer autoloader
require __DIR__.'/vendor/autoload.php';

// Bootstrap the app
$app = require_once __DIR__.'/bootstrap/app.php';

// Handle the request
$app->handleRequest(Request::capture());
