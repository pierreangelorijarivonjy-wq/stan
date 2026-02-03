<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== Liste des utilisateurs ===\n\n";

$users = User::with('roles')->get();

foreach ($users as $user) {
    $roles = $user->getRoleNames()->implode(', ');
    echo "ID: {$user->id} | {$user->name} | {$user->email} | Rôles: {$roles}\n";

    // Réinitialiser le mot de passe selon le rôle
    if ($user->hasRole('admin')) {
        $user->password = Hash::make('admin@edupass');
        $user->save();
        echo "  ✅ Mot de passe réinitialisé: admin@edupass\n";
    } elseif ($user->hasRole('comptable')) {
        $user->password = Hash::make('comptable@edupass');
        $user->save();
        echo "  ✅ Mot de passe réinitialisé: comptable@edupass\n";
    } elseif ($user->hasRole('scolarite')) {
        $user->password = Hash::make('scolarite@edupass');
        $user->save();
        echo "  ✅ Mot de passe réinitialisé: scolarite@edupass\n";
    } elseif ($user->hasRole('student')) {
        $user->password = Hash::make('password');
        $user->save();
        echo "  ✅ Mot de passe réinitialisé: password\n";
    }
    echo "\n";
}

echo "=== Terminé ===\n";
