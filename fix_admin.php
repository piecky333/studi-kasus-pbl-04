<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$user = App\Models\User::where('email', 'admin@politala.ac.id')->first();
if (!$user) {
    echo "User admin@politala.ac.id not found. Please run seeders first.\n";
    exit;
}

echo "User found: ID " . $user->id_user . "\n";

$admin = App\Models\admin\admin::where('id_user', $user->id_user)->first();

if ($admin) {
    echo "Admin record already exists. ID: " . $admin->id_admin . "\n";
} else {
    echo "Creating missing Admin record...\n";
    try {
        $newAdmin = App\Models\admin\admin::create([
            'id_user' => $user->id_user,
            'nama_admin' => 'Admin Sistem',
            'jabatan_admin' => 'Administrator',
        ]);
        echo "Successfully created Admin record. ID: " . $newAdmin->id_admin . "\n";
    } catch (\Exception $e) {
        echo "Error creating admin record: " . $e->getMessage() . "\n";
    }
}
