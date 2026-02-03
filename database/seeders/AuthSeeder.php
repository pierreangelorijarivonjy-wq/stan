<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // S'assurer que les rôles existent
        $roles = ['admin', 'comptable', 'scolarite', 'controleur', 'student'];
        foreach ($roles as $roleName) {
            Role::findOrCreate($roleName);
        }

        // Nettoyer les anciens comptes staff pour éviter les conflits de matricule
        User::role(['admin', 'comptable', 'scolarite'])->delete();

        // 1. ADMIN
        $admins = [
            ['email' => 'pierreangelorijarivonjy@gmail.com', 'matricule' => 'ADM-UF-2025-001', 'name' => 'Admin Principal'],
            ['email' => 'soozey.officiel@gmail.com', 'matricule' => 'ADM-UF-2025-002', 'name' => 'Admin Soozey'],
        ];
        foreach ($admins as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('admin@edupass'),
                    'email_verified_at' => now(),
                    'matricule' => $data['matricule'],
                    'status' => 'active',
                ]
            );
            $user->syncRoles(['admin']);
        }

        // 2. COMPTABLE
        $comptables = [
            ['email' => 'a60242792@gmail.com', 'matricule' => 'COM-UF-2025-001', 'name' => 'Comptable Officiel'],
        ];
        foreach ($comptables as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('comptable@edupass'),
                    'email_verified_at' => now(),
                    'matricule' => $data['matricule'],
                    'status' => 'active',
                ]
            );
            $user->syncRoles(['comptable']);
        }

        // 3. SCOLARITE
        $scolarites = [
            ['email' => 'n4089013@gmail.com', 'matricule' => 'SCO-UF-2025-001', 'name' => 'Scolarité Officiel'],
        ];
        foreach ($scolarites as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('scolarite@edupass'),
                    'email_verified_at' => now(),
                    'matricule' => $data['matricule'],
                    'status' => 'active',
                ]
            );
            $user->syncRoles(['scolarite']);
        }

        echo "Comptes institutionnels mis à jour avec succès.\n";
    }
}
