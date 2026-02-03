<?php

namespace Tests\Feature;

use App\Models\Convocation;
use App\Models\ExamSession;
use App\Models\Student;
use App\Models\User;
use App\Mail\ConvocationMail;
use App\Notifications\ConvocationReady;
use App\Services\SmsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class ConvocationNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup roles and permissions
        $permission = \Spatie\Permission\Models\Permission::create(['name' => 'generate convocations']);
        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo($permission);
        Role::create(['name' => 'student']);

        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin);
        session(['auth.two_factor_verified' => true]);
    }

    public function test_can_send_convocations_via_all_channels()
    {
        $this->withoutExceptionHandling();
        Mail::fake();
        Notification::fake();

        // Mock Log to verify SMS simulation
        Log::shouldReceive('info')
            ->once()
            ->withArgs(function ($message) {
                return str_contains($message, 'SMS SIMULATION to 261340000001') &&
                    str_contains($message, 'Votre convocation');
            });

        $student = Student::factory()->create([
            'email' => 'student@example.com',
            'phone' => '261340000001',
        ]);
        $student->user->assignRole('student');

        $session = ExamSession::factory()->create();

        $convocation = Convocation::create([
            'student_id' => $student->id,
            'exam_session_id' => $session->id,
            'qr_code' => 'test-qr-code',
            'status' => 'generated',
        ]);

        $response = $this->from(route('admin.convocations.create'))->post(route('admin.convocations.send'), [
            'convocation_ids' => [$convocation->id],
            'channels' => ['email', 'sms', 'in_app'],
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verify Email
        Mail::assertQueued(ConvocationMail::class, function ($mail) use ($student) {
            return $mail->hasTo($student->email);
        });

        // Verify In-app Notification
        Notification::assertSentTo(
            $student->user,
            ConvocationReady::class
        );

        // Verify Status Update
        $this->assertEquals('sent', $convocation->fresh()->status);
        $this->assertNotNull($convocation->fresh()->sent_at);
    }
}
