<?php

namespace Tests\Feature;

use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeSessionTest extends TestCase
{
    use RefreshDatabase;

    private function makeEmployee(string $id, ?string $email = null): Employee
    {
        return Employee::create([
            'employee_id' => $id,
            'name' => 'Test ' . $id,
            'email' => $email ?? strtolower($id) . '@test.com',
            'designation' => 'Developer',
        ]);
    }

    public function test_dashboard_requires_session(): void
    {
        $this->makeEmployee('EMP-TEST');

        $this->get(route('employee.dashboard', ['employee_id' => 'EMP-TEST']))
            ->assertRedirect(route('employee.login'));
    }

    public function test_lookup_creates_session_and_dashboard_accessible(): void
    {
        $this->makeEmployee('EMP-TEST');

        $this->post(route('employee.lookup'), ['employee_id' => 'EMP-TEST'])
            ->assertOk()
            ->assertJson(['found' => true]);

        $this->get(route('employee.dashboard', ['employee_id' => 'EMP-TEST']))
            ->assertOk();
    }

    public function test_dashboard_checks_session_match(): void
    {
        $this->makeEmployee('EMP-A');
        $this->makeEmployee('EMP-B');

        // logged in as EMP-A, trying to access EMP-B dashboard
        $this->post(route('employee.lookup'), ['employee_id' => 'EMP-A']);
        $this->get(route('employee.dashboard', ['employee_id' => 'EMP-B']))
            ->assertRedirect(route('employee.login'));
    }

    public function test_logout_clears_session_and_redirects_home(): void
    {
        $this->makeEmployee('EMP-TEST');

        $this->post(route('employee.lookup'), ['employee_id' => 'EMP-TEST']);
        $this->post(route('employee.logout'))->assertRedirect('/');

        $this->get(route('employee.dashboard', ['employee_id' => 'EMP-TEST']))
            ->assertRedirect(route('employee.login'));
    }
}
