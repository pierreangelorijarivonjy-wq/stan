<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

$users = User::all();
foreach ($users as $user) {
    echo "ID: {$user->id} | Email: {$user->email} | Matricule: {$user->matricule} | Roles: " . implode(', ', $user->getRoleNames()->toArray()) . PHP_EOL;
}
