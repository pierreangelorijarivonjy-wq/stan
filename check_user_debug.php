<?php

use App\Models\User;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$usersWithoutRoles = User::doesntHave('roles')->get();

if ($usersWithoutRoles->count() > 0) {
    echo "Found " . $usersWithoutRoles->count() . " users without roles. Assigning 'student' role...\n";
    foreach ($usersWithoutRoles as $u) {
        $u->assignRole('student');
        echo "- Assigned 'student' role to: " . $u->email . "\n";
    }
    echo "Done.\n";
} else {
    echo "No users found without roles.\n";
}
