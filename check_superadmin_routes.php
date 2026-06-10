<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$routes = app('router')->getRoutes();
foreach ($routes as $route) {
    if (strpos($route->getName(), 'superadmin.users') !== false) {
        echo $route->getName() . " -> " . $route->uri() . "\n";
    }
}
