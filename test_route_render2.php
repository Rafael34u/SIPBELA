<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

// Bootstrap the HTTP kernel to set up the environment properly
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('role', 'superadmin')->first();
if (!$user) {
    echo "No superadmin found.\n";
    exit;
}

auth()->login($user);

$request = Illuminate\Http\Request::create('/superadmin/users/create', 'GET');
$response = $kernel->handle($request);

echo "Create Status: " . $response->getStatusCode() . "\n";
if ($response->isRedirection()) {
    echo "Redirect URL: " . $response->getTargetUrl() . "\n";
}

$siswa = \App\Models\User::where('role', 'siswa')->first();
if ($siswa) {
    $requestEdit = Illuminate\Http\Request::create('/superadmin/users/' . $siswa->id . '/edit', 'GET');
    $responseEdit = $kernel->handle($requestEdit);
    echo "Edit Status: " . $responseEdit->getStatusCode() . "\n";
    if ($responseEdit->isRedirection()) {
        echo "Redirect URL: " . $responseEdit->getTargetUrl() . "\n";
    }
}
