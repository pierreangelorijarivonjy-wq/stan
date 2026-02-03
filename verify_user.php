<?php

use App\Models\User;
use Illuminate\Support\Facades\Artisan;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = 'pierreangelorijarivonjy@gmail.com';
$user = User::where('email', $email)->first();

if ($user) {
    $user->email_verified_at = now();
    $user->save();
    echo "L'email $email a été vérifié avec succès dans la base de données.\n";
} else {
    echo "Utilisateur avec l'email $email non trouvé.\n";
}
