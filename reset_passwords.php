<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== Réinitialisation des mots de passe ===\n\n";

// Admin
$admin = User::where('email', 'admin@edupass-mg.com')->first();
if ($admin) {
    $admin->password = Hash::make('admin@edupass');
    $admin->save();
    echo "✅ Admin: admin@edupass-mg.com - Mot de passe: admin@edupass\n";
} else {
    echo "❌ Admin non trouvé\n";
}

// Comptable
$comptable = User::where('email', 'comptable@edupass-mg.com')->first();
if ($comptable) {
    $comptable->password = Hash::make('comptable@edupass');
    $comptable->save();
    echo "✅ Comptable: comptable@edupass-mg.com - Mot de passe: comptable@edupass\n";
} else {
    echo "❌ Comptable non trouvé\n";
}

// Scolarité
$scolarite = User::where('email', 'scolarite@edupass-mg.com')->first();
if ($scolarite) {
    $scolarite->password = Hash::make('scolarite@edupass');
    $scolarite->save();
    echo "✅ Scolarité: scolarite@edupass-mg.com - Mot de passe: scolarite@edupass\n";
} else {
    echo "❌ Scolarité non trouvé\n";
}

echo "\n=== Terminé ===\n";
