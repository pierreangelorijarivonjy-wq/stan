<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class LmsPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create LMS Permissions
        $permissions = [
            // Course Management (Scolarité)
            'manage courses',
            'create courses',
            'edit courses',
            'delete courses',
            'publish courses',

            // Module & Lesson Management
            'manage modules',
            'manage lessons',

            // Resource Management
            'upload resources',
            'delete resources',

            // Quiz Management
            'manage quizzes',
            'create quizzes',
            'grade quizzes',

            // Student Progress Tracking
            'view student progress',
            'export student data',

            // Student Permissions
            'view courses',
            'enroll courses',
            'take quizzes',
            'download resources',
            'view own progress',

            // Admin Permissions
            'manage system',
            'view logs',
            'manage backups',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create or update Scolarité role
        $scolarite = Role::firstOrCreate(['name' => 'scolarite']);
        $scolarite->givePermissionTo([
            'manage courses',
            'create courses',
            'edit courses',
            'delete courses',
            'publish courses',
            'manage modules',
            'manage lessons',
            'upload resources',
            'delete resources',
            'manage quizzes',
            'create quizzes',
            'grade quizzes',
            'view student progress',
            'export student data',
        ]);

        // Create or update Student role
        $student = Role::firstOrCreate(['name' => 'student']);
        $student->givePermissionTo([
            'view courses',
            'enroll courses',
            'take quizzes',
            'download resources',
            'view own progress',
        ]);

        // Create or update Admin role
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo([
            'manage system',
            'view logs',
            'manage backups',
            'view student progress', // Admin can monitor
        ]);

        $this->command->info('LMS Permissions and Roles created successfully!');
    }
}
