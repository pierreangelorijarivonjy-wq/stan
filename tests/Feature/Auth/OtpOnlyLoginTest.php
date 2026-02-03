<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Services\OtpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class OtpOnlyLoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolesPermissionsSeeder::class);
    }

    public function test_user_can_request_otp_without_password()
    {
        $user = User::factory()->create([
            'email' => 'test@edupass.mg',
            'status' => 'active',
        ]);
        $user->assignRole('student');

        $response = $this->post('/login', [
            'email' => 'test@edupass.mg',
        ]);

        $response->assertRedirect(route('otp.show'));
        $this->assertEquals($user->id, session('login.id'));
        $this->assertFalse(Auth::check());
    }

    public function test_user_is_logged_in_only_after_otp_verification()
    {
        $user = User::factory()->create([
            'email' => 'test@edupass.mg',
            'status' => 'active',
        ]);
        $user->assignRole('student');

        // Step 1: Request OTP
        $this->post('/login', ['email' => 'test@edupass.mg']);

        $user->refresh();
        $otp = $user->otp_code;
        $this->assertNotNull($otp);

        // Step 2: Verify OTP
        $response = $this->post('/verify-otp', [
            'otp' => $otp,
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertTrue(Auth::check());
        $this->assertEquals($user->id, Auth::id());
    }

    public function test_registration_is_enabled()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'new@edupass.mg',
            'phone' => '0340000000',
            'role_type' => 'student',
            'matricule' => 'MAT-2025-001',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $response->assertRedirect(route('otp.show'));
    }

    public function test_admin_login_also_uses_otp_only()
    {
        $admin = User::factory()->create([
            'email' => 'admin@edupass.mg',
            'status' => 'active',
        ]);
        $admin->assignRole('admin');

        $response = $this->post('/admin/login', [
            'email' => 'admin@edupass.mg',
        ]);

        $response->assertRedirect(route('otp.show'));
        $this->assertEquals($admin->id, session('login.id'));
        $this->assertFalse(Auth::check());
    }
}
