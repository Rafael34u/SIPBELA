<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$user = \App\Models\User::where('role', 'superadmin')->first();
if (!$user) {
    echo "No superadmin found.\n";
    exit;
}

auth()->login($user);

$request = Illuminate\Http\Request::create('/superadmin/users/create', 'GET');
$response = $kernel->handle($request);

echo "Status: " . $response->getStatusCode() . "\n";
if ($response->isRedirection()) {
    echo "Redirect URL: " . $response->getTargetUrl() . "\n";
} else {
    echo "View Rendered OK.\n";
}
