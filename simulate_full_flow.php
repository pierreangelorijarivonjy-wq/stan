<?php

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Student;
use App\Models\Payment;
use App\Models\ExamSession;
use App\Models\Convocation;
use App\Models\AuditLog;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n=======================================================\n";
echo "🚀  DÉMARRAGE DE LA SIMULATION DU PARCOURS COMPLET\n";
echo "=======================================================\n\n";

try {
    DB::beginTransaction();

    // 1. Création Étudiant
    echo "1️⃣  INSCRIPTION ÉTUDIANT\n";
    $email = 'etudiant_' . time() . '@edupass.mg';
    $matricule = 'MAT-' . rand(10000, 99999);

    echo "   -> Création compte utilisateur ($email)...\n";
    $user = User::create([
        'name' => 'Jean Testeur',
        'email' => $email,
        'password' => bcrypt('password123'),
        'phone' => '034' . rand(1000000, 9999999),
        'status' => 'active',
        'email_verified_at' => now()
    ]);
    $user->assignRole('student');
    Auth::login($user); // Login to ensure Auditable works with a user

    echo "   -> Création profil étudiant ($matricule)...\n";
    $student = Student::create([
        'user_id' => $user->id,
        'matricule' => $matricule,
        'first_name' => 'Jean',
        'last_name' => 'Testeur',
        'email' => $email,
        'phone' => $user->phone,
        'status' => 'active'
    ]);
    echo "   ✅ Compte créé avec succès.\n\n";

    // 2. Paiement
    echo "2️⃣  PAIEMENT (Simulation MVola)\n";
    $amount = 50000;
    $reference = 'EDUPASS-' . time() . '-' . $user->id;

    echo "   -> Initialisation paiement de {$amount} Ar...\n";
    $payment = Payment::create([
        'user_id' => $user->id,
        'amount' => $amount,
        'phone' => $user->phone,
        'provider' => 'mvola',
        'transaction_id' => $reference,
        'type' => 'frais_scolarite',
        'status' => 'pending'
    ]);

    echo "   -> Réception Webhook (Simulation succès)...\n";
    // Simulation logique Webhook
    $payment->update([
        'status' => 'paid',
        'paid_at' => now()
    ]);

    // Simulation génération signature reçu
    $signatureData = ['id' => $payment->id, 'secret' => config('app.key')];
    $sig = hash_hmac('sha256', json_encode($signatureData), config('app.key'));
    $payment->update(['metadata' => ['digital_signature' => $sig]]);

    AuditLog::create([
        'event' => 'payment_completed',
        'auditable_type' => Payment::class,
        'auditable_id' => $payment->id,
        'user_id' => $user->id,
        'description' => "Paiement simulé réussi",
        'ip_address' => '127.0.0.1',
        'user_agent' => 'SimulationScript'
    ]);

    echo "   ✅ Paiement validé. Statut : PAID\n";
    echo "   ✅ Signature numérique : " . substr($sig, 0, 16) . "...\n\n";

    // 3. Session Examen
    echo "3️⃣  GESTION EXAMEN (Admin)\n";
    echo "   -> Planification session...\n";

    $session = ExamSession::create([
        'type' => 'exam', // Must be one of: exam, regroupement, orientation
        'center' => 'Lycée Moderne Tana',
        'date' => now()->addDays(10),
        'time' => '08:00',
        'room' => 'Salle A12',
        'status' => 'planned'
    ]);
    echo "   ✅ Session créée : {$session->type} le {$session->date->format('d/m/Y')}\n\n";

    // 4. Convocation
    echo "4️⃣  GÉNÉRATION CONVOCATION\n";
    echo "   -> Génération PDF et QR Code...\n";

    $qrCode = Str::uuid()->toString();

    // Mock signature convocation
    $convSigData = ['qr' => $qrCode, 'student' => $student->id];
    $convSig = hash_hmac('sha256', json_encode($convSigData), config('app.key'));

    $convocation = Convocation::create([
        'student_id' => $student->id,
        'exam_session_id' => $session->id,
        'qr_code' => $qrCode,
        'status' => 'generated',
        'signature' => $convSig
    ]);

    AuditLog::create([
        'event' => 'convocation_generated',
        'auditable_type' => Convocation::class,
        'auditable_id' => $convocation->id,
        'description' => "Convocation générée (Simulation)",
        'ip_address' => '127.0.0.1',
        'user_agent' => 'SimulationScript'
    ]);

    echo "   ✅ Convocation prête. QR Code : {$qrCode}\n";
    echo "   ✅ Notification envoyée (Simulée)\n\n";

    // 5. Scan Contrôleur
    echo "5️⃣  CONTRÔLE D'ACCÈS (Scan QR)\n";
    echo "   -> Le contrôleur scanne le code {$qrCode}...\n";

    $check = Convocation::where('qr_code', $qrCode)->first();

    if ($check && $check->status !== 'deactivated') {
        $check->update(['scanned_at' => now()]);

        echo "   🟢 RÉSULTAT SCAN : VALIDE\n";
        echo "   👤 Étudiant : {$check->student->first_name} {$check->student->last_name}\n";
        echo "   📅 Session : {$check->examSession->type}\n";
        echo "   🕒 Heure Scan : " . now()->format('H:i:s') . "\n";
    } else {
        echo "   🔴 RÉSULTAT SCAN : INVALIDE\n";
    }

    DB::commit();
    echo "\n=======================================================\n";
    echo "✨  SIMULATION TERMINÉE AVEC SUCCÈS\n";
    echo "=======================================================\n";

} catch (\Exception $e) {
    DB::rollBack();
    echo "\n❌ ERREUR FATALE : " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
    echo $e->getTraceAsString();
}
