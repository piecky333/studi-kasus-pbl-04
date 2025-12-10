<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\admin\admin;
use App\Models\User;

$admins = admin::all();
echo "Total Admins in 'admin' table: " . $admins->count() . "\n";

foreach ($admins as $a) {
    echo "ID Admin: $a->id_admin | Nama: $a->nama_admin | ID User: $a->id_user\n";
}

$users = User::where('role', 'admin')->get();
echo "\nTotal Users with role 'admin': " . $users->count() . "\n";
foreach ($users as $u) {
    echo "ID User: $u->id_user | Nama: $u->nama | Has Admin Record: " . ($u->admin ? 'YES' : 'NO') . "\n";
}
