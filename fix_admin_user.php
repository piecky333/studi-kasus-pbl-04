<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\admin\admin;
use App\Models\User;

$user = User::find(1);

if (!$user) {
    echo "User 1 not found!\n";
    exit;
}

if ($user->admin) {
    echo "User 1 already has admin record (ID: " . $user->admin->id_admin . ").\n";
} else {
    echo "Creating admin record for User 1...\n";
    admin::create([
        'id_user' => 1,
        'nama_admin' => $user->nama ?? 'Super Admin',
    ]);
    echo "Admin record created successfully.\n";
}
