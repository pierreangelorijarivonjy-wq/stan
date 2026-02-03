<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Student;
use App\Models\Payment;
use App\Models\Convocation;
use App\Models\ExamSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles and permissions
        $this->seed(\Database\Seeders\RolesPermissionsSeeder::class);
    }

    public function test_student_cannot_access_admin_routes()
    {
        $student = User::factory()->create();
        $student->assignRole('student');

        $this->actingAs($student);
        session(['auth.two_factor_verified' => true]);

        $this->get(route('admin.reconciliation.index'))->assertStatus(403);
        $this->get(route('admin.convocations.create'))->assertStatus(403);
    }

    public function test_comptable_cannot_access_convocation_management()
    {
        $comptable = User::factory()->create();
        $comptable->assignRole('comptable');

        $this->actingAs($comptable);
        session(['auth.two_factor_verified' => true]);

        $this->get(route('admin.reconciliation.index'))->assertStatus(200);
        $this->get(route('admin.convocations.create'))->assertStatus(403);
    }

    public function test_scolarite_cannot_access_reconciliation()
    {
        $scolarite = User::factory()->create();
        $scolarite->assignRole('scolarite');

        $this->actingAs($scolarite);
        session(['auth.two_factor_verified' => true]);

        $this->get(route('admin.convocations.create'))->assertStatus(200);
        $this->get(route('admin.reconciliation.index'))->assertStatus(403);
    }

    public function test_student_can_only_see_their_own_receipts()
    {
        $student1 = User::factory()->create();
        $student1->assignRole('student');

        $student2 = User::factory()->create();
        $student2->assignRole('student');

        $payment2 = Payment::factory()->create(['user_id' => $student2->id]);

        $this->actingAs($student1);
        session(['auth.two_factor_verified' => true]);

        $this->get(route('payments.receipt', $payment2))->assertStatus(403);
    }
}
