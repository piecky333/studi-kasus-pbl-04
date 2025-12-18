<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$user = App\Models\User::where('email', 'admin@politala.ac.id')->first();
if (!$user) {
    echo "User not found.\n";
    exit;
}
echo "User found: ID " . $user->id_user . "\n";

$admin = App\Models\admin\admin::where('id_user', $user->id_user)->first();
if ($admin) {
    echo "Admin record found: ID " . $admin->id_admin . "\n";
} else {
    echo "Admin record NOT found for this user.\n";
    // Try to find ANY admin record
    $anyAdmin = App\Models\admin\admin::first();
    if ($anyAdmin) {
        echo "Sample admin record exists (ID User: " . $anyAdmin->id_user . ")\n";
    } else {
        echo "Admin table is empty.\n";
    }
}
