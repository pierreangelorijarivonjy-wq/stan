<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PortalSeparationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Créer les rôles nécessaires
        Role::create(['name' => 'student']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'comptable']);
    }

    public function test_student_can_login_via_student_portal_and_needs_otp(): void
    {
        $student = User::factory()->create();
        $student->assignRole('student');

        $response = $this->post('/login', [
            'email' => $student->email,
            'password' => 'password',
        ]);

        // Redirige vers le 2FA (flux obligatoire étudiant)
        $response->assertRedirect(route('two-factor.login'));
        $this->assertNotNull(session('auth.2fa_user_id'));
    }

    public function test_admin_can_login_and_needs_otp(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        // Redirige vers le 2FA (flux obligatoire pour tous)
        $response->assertRedirect(route('two-factor.login'));
        $this->assertNotNull(session('auth.2fa_user_id'));
    }

    public function test_comptable_can_login_and_needs_otp(): void
    {
        $comptable = User::factory()->create();
        $comptable->assignRole('comptable');

        $response = $this->post('/login', [
            'email' => $comptable->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('two-factor.login'));
    }

    public function test_scolarite_can_login_and_needs_otp(): void
    {
        Role::findOrCreate('scolarite');
        $sco = User::factory()->create();
        $sco->assignRole('scolarite');

        $response = $this->post('/login', [
            'email' => $sco->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('two-factor.login'));
    }

    public function test_student_is_rejected_at_admin_portal(): void
    {
        $student = User::factory()->create();
        $student->assignRole('student');

        $response = $this->post('/admin/login', [
            'email' => $student->email,
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertEquals(
            'Accès refusé. Ce portail est réservé au personnel administratif.',
            session('errors')->get('email')[0]
        );
        $this->assertGuest();
    }
}
