<?php
$users = App\Models\User::where('role', 'admin')->with('admin')->get();
foreach ($users as $u) {
    echo "ID: " . $u->id_user . " | Name: " . $u->nama . " | Role: " . $u->role . " | Has Admin Relation: " . ($u->admin ? 'YES' : 'NO') . "\n";
}
