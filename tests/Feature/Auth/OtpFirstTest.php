<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class OtpFirstTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'student']);
    }

    public function test_student_login_without_password_sends_random_otp()
    {
        $user = User::factory()->create([
            'email' => 'student@example.com',
        ]);
        $user->assignRole('student');

        $response = $this->post('/login', [
            'email' => 'student@example.com',
            'password' => '',
        ]);

        $response->assertRedirect(route('otp.show'));
        $user->refresh();
        $this->assertNotNull($user->otp_code);
        $this->assertEquals(6, strlen($user->otp_code));
        $this->assertTrue(is_numeric($user->otp_code));
    }

    public function test_staff_login_requires_password_and_bypasses_otp()
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);
        $user->assignRole('admin');

        $response = $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => '',
        ]);
        $response->assertSessionHasErrors('password');

        $response = $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertNull($user->fresh()->otp_code);
        $this->assertTrue(session('auth.two_factor_verified'));
        $this->assertEquals($user->id, Auth::id());
    }

    public function test_student_account_switcher_requires_otp()
    {
        $user1 = User::factory()->create(['email' => 'user1@example.com']);
        $user2 = User::factory()->create(['email' => 'student@example.com']);
        $user2->assignRole('student');

        $this->actingAs($user1);

        $response = $this->post('/switch-account', [
            'email' => 'student@example.com',
        ]);

        $response->assertRedirect(route('otp.show'));
        $this->assertEquals($user2->id, Auth::id());

        $user2->refresh();
        $this->assertNotNull($user2->otp_code);
    }

    public function test_staff_account_switcher_requires_password()
    {
        $user1 = User::factory()->create(['email' => 'user1@example.com']);
        $user2 = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);
        $user2->assignRole('admin');

        $this->actingAs($user1);

        $response = $this->post('/switch-account', [
            'email' => 'admin@example.com',
        ]);

        $response->assertRedirect(route('account.switch.password'));
        $this->assertEquals($user2->id, session('auth.switch_user_id'));

        $response = $this->post('/switch-password', [
            'password' => 'wrong-password',
        ]);
        $response->assertSessionHasErrors('password');

        $response = $this->post('/switch-password', [
            'password' => 'password123',
        ]);
        $response->assertRedirect(route('dashboard'));
        $this->assertEquals($user2->id, Auth::id());
        $this->assertTrue(session('auth.two_factor_verified'));
    }
}
