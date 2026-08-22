<?php

namespace Tests\Feature;

use App\Models\Designation;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DesignationUpdateCascadeTest extends TestCase
{
    use RefreshDatabase;

    public function test_updating_designation_cascades_to_employees(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create Designation
        $designation = Designation::create([
            'title' => 'Admin',
            'department' => 'Administration',
        ]);

        // Create Employees with this designation
        $emp1 = Employee::create([
            'employee_id' => 'EMP-010',
            'name' => 'Alice Admin',
            'email' => 'alice@example.com',
            'designation' => 'Admin',
        ]);

        $emp2 = Employee::create([
            'employee_id' => 'EMP-011',
            'name' => 'Bob Admin',
            'email' => 'bob@example.com',
            'designation' => 'Admin',
        ]);

        // Create Employee with different designation
        $emp3 = Employee::create([
            'employee_id' => 'EMP-012',
            'name' => 'Charlie Dev',
            'email' => 'charlie@example.com',
            'designation' => 'Developer',
        ]);

        // Update Designation title from "Admin" to "Office Admin"
        $response = $this->post(route('admin.api.designations.update'), [
            'id' => $designation->id,
            'title' => 'Office Admin',
            'department' => 'Administration',
        ]);

        $response->assertOk()->assertJson(['success' => true]);

        // Verify Designation model was updated
        $designation->refresh();
        $this->assertEquals('Office Admin', $designation->title);

        // Verify employees with old designation title were updated
        $emp1->refresh();
        $emp2->refresh();
        $emp3->refresh();

        $this->assertEquals('Office Admin', $emp1->designation);
        $this->assertEquals('Office Admin', $emp2->designation);
        $this->assertEquals('Developer', $emp3->designation);
    }
}
