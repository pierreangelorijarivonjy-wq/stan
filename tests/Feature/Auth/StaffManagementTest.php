<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class StaffManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'comptable']);
        Role::create(['name' => 'scolarite']);
        Role::create(['name' => 'student']);
    }

    public function test_cannot_create_more_than_three_admins(): void
    {
        // Créer 3 admins
        for ($i = 1; $i <= 3; $i++) {
            User::createStaff([
                'name' => "Admin $i",
                'email' => "admin$i@example.com",
                'password' => 'password',
                'matricule' => "ADM-00$i",
            ], 'admin');
        }

        $this->assertEquals(3, User::role('admin')->count());

        // Tenter de créer un 4ème
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("La limite de 3 comptes pour le rôle admin est atteinte.");

        User::createStaff([
            'name' => "Admin 4",
            'email' => "admin4@example.com",
            'password' => 'password',
            'matricule' => "ADM-004",
        ], 'admin');
    }

    public function test_staff_recovery_works_with_correct_credentials(): void
    {
        $admin = User::createStaff([
            'name' => 'Admin Test',
            'email' => 'admin@example.com',
            'password' => 'old_password',
            'matricule' => 'ADM-TEST-001',
        ], 'admin');

        $response = $this->post('/staff-recovery', [
            'email' => 'admin@example.com',
            'matricule' => 'ADM-TEST-001',
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('status');

        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('new_password', $admin->fresh()->password));
    }

    public function test_staff_recovery_fails_with_incorrect_matricule(): void
    {
        $admin = User::createStaff([
            'name' => 'Admin Test',
            'email' => 'admin@example.com',
            'password' => 'password',
            'matricule' => 'ADM-TEST-001',
        ], 'admin');

        $response = $this->post('/staff-recovery', [
            'email' => 'admin@example.com',
            'matricule' => 'WRONG-MATRICULE',
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ]);

        $response->assertSessionHasErrors('matricule');
        $this->assertFalse(\Illuminate\Support\Facades\Hash::check('new_password', $admin->fresh()->password));
    }

    public function test_student_cannot_use_staff_recovery(): void
    {
        $student = User::create([
            'name' => 'Student Test',
            'email' => 'student@example.com',
            'password' => 'password',
            'matricule' => 'STU-001',
        ]);
        $student->assignRole('student');

        $response = $this->post('/staff-recovery', [
            'email' => 'student@example.com',
            'matricule' => 'STU-001',
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertEquals(
            'Cette méthode de récupération est réservée au personnel administratif.',
            session('errors')->get('email')[0]
        );
    }
}
