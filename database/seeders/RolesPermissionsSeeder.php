<?php

// database/seeders/RolesPermissionsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Création des permissions de base (à étendre plus tard)
        $permissions = [
            'manage payments',
            'manage convocations',
            'generate convocations',
            'view reconciliation',
            'run reconciliation',
            'manage students',
            'view dashboard',
            'verify convocation',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Rôles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions(Permission::all());

        $comptable = Role::firstOrCreate(['name' => 'comptable']);
        $comptable->syncPermissions(['manage payments', 'view reconciliation', 'run reconciliation', 'view dashboard']);

        $scolarite = Role::firstOrCreate(['name' => 'scolarite']);
        $scolarite->syncPermissions(['manage convocations', 'generate convocations', 'manage students', 'view dashboard']);

        $controleur = Role::firstOrCreate(['name' => 'controleur']);
        $controleur->syncPermissions(['verify convocation']);

        $student = Role::firstOrCreate(['name' => 'student']);
        $student->syncPermissions(['view dashboard']);
    }
}