<?php

namespace Tests\Feature;

use App\Models\ExamSession;
use App\Models\Student;
use App\Models\User;
use App\Models\Convocation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ConvocationFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Setup permissions
        $role = Role::create(['name' => 'admin']);
        Permission::create(['name' => 'generate convocations']);
        $role->givePermissionTo('generate convocations');
    }

    public function test_admin_can_generate_convocations()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $session = ExamSession::factory()->create();
        $student = Student::factory()->create();

        $response = $this->actingAsVerified($admin)->post(route('admin.convocations.generate'), [
            'exam_session_id' => $session->id,
            'student_ids' => [$student->id],
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('convocations', [
            'student_id' => $student->id,
            'exam_session_id' => $session->id,
            'status' => 'generated',
        ]);
    }

    public function test_sending_convocation_queues_email()
    {
        Mail::fake();

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $convocation = Convocation::create([
            'student_id' => Student::factory()->create()->id,
            'exam_session_id' => ExamSession::factory()->create()->id,
            'qr_code' => 'TEST-QR',
            'status' => 'generated'
        ]);

        $response = $this->actingAsVerified($admin)->post(route('admin.convocations.send'), [
            'convocation_ids' => [$convocation->id],
            'channels' => ['email'],
        ]);

        Mail::assertQueued(\App\Mail\ConvocationMail::class);

        $this->assertDatabaseHas('convocations', [
            'id' => $convocation->id,
            'status' => 'sent',
        ]);
    }
}
