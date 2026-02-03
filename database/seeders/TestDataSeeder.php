<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use App\Models\ExamSession;
use App\Models\Payment;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Créer utilisateurs de test pour chaque rôle
        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@edupass.mg',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        $comptable = User::create([
            'name' => 'Comptable Test',
            'email' => 'comptable@edupass.mg',
            'password' => Hash::make('password'),
        ]);
        $comptable->assignRole('comptable');

        $scolarite = User::create([
            'name' => 'Scolarité Test',
            'email' => 'scolarite@edupass.mg',
            'password' => Hash::make('password'),
        ]);
        $scolarite->assignRole('scolarite');

        // Créer 5 étudiants
        for ($i = 1; $i <= 5; $i++) {
            $user = User::create([
                'name' => "Étudiant Test {$i}",
                'email' => "etudiant{$i}@edupass.mg",
                'password' => Hash::make('password'),
            ]);
            $user->assignRole('student');

            $student = Student::create([
                'user_id' => $user->id,
                'matricule' => 'ETU' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'first_name' => "Prénom{$i}",
                'last_name' => "Nom{$i}",
                'email' => "etudiant{$i}@edupass.mg",
                'phone' => '034' . rand(1000000, 9999999),
                'piece_id' => 'CIN' . rand(100000000, 999999999),
                'status' => 'active',
            ]);

            // Créer des paiements
            Payment::create([
                'user_id' => $user->id,
                'type' => 'inscription',
                'provider' => $i % 2 === 0 ? 'mvola' : 'orange',
                'phone' => $student->phone,
                'amount' => 50000,
                'transaction_id' => 'EDUPASS-' . time() . '-' . $i,
                'status' => $i <= 3 ? 'paid' : 'pending',
                'method' => 'mobile_money',
                'paid_at' => $i <= 3 ? now() : null,
            ]);
        }

        // Créer des sessions d'examen
        ExamSession::create([
            'type' => 'exam',
            'center' => 'Antananarivo',
            'date' => now()->addDays(30),
            'time' => '08:00:00',
            'room' => 'Salle A',
            'status' => 'planned',
        ]);

        ExamSession::create([
            'type' => 'regroupement',
            'center' => 'Toamasina',
            'date' => now()->addDays(45),
            'time' => '14:00:00',
            'room' => 'Salle B',
            'status' => 'planned',
        ]);

        $this->command->info('✅ Données de test créées avec succès!');
        $this->command->info('');
        $this->command->info('📧 Comptes créés:');
        $this->command->info('   Admin: admin@edupass.mg / password');
        $this->command->info('   Comptable: comptable@edupass.mg / password');
        $this->command->info('   Scolarité: scolarite@edupass.mg / password');
        $this->command->info('   Étudiants: etudiant1-5@edupass.mg / password');
    }
}
