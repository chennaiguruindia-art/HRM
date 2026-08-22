<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceLogicTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_working_hours_status_calculations(): void
    {
        $base = Carbon::parse('2026-08-22 00:00:00');

        // >= 8 hours -> present
        $this->assertEquals('present', Attendance::calculateStatus($base->copy()->setHour(9), $base->copy()->setHour(17)->setMinute(30))); // 8.5h
        $this->assertEquals('present', Attendance::calculateStatus($base->copy()->setHour(9), $base->copy()->setHour(17))); // 8.0h

        // 5 to 8 hours -> half-day
        $this->assertEquals('half-day', Attendance::calculateStatus($base->copy()->setHour(9), $base->copy()->setHour(14)->setMinute(30))); // 5.5h
        $this->assertEquals('half-day', Attendance::calculateStatus($base->copy()->setHour(9), $base->copy()->setHour(14))); // 5.0h

        // < 5 hours -> absent
        $this->assertEquals('absent', Attendance::calculateStatus($base->copy()->setHour(10), $base->copy()->setHour(14))); // 4.0h
    }

    public function test_auto_clock_out_for_past_unclosed_attendance(): void
    {
        $yesterday = Carbon::yesterday();

        $att = Attendance::create([
            'employee_id' => 'EMP-001',
            'date' => $yesterday,
            'check_in' => $yesterday->copy()->setHour(9)->setMinute(0),
            'check_out' => null,
            'status' => 'present',
        ]);

        Attendance::processAutoClockOuts();

        $att->refresh();
        $this->assertNotNull($att->check_out);
        $this->assertEquals('18:30:00', Carbon::parse($att->check_out)->format('H:i:s'));
        $this->assertEquals('present', $att->status); // 9:00 to 18:30 = 9.5 hours
    }

    public function test_auto_clock_out_for_today_after_630_pm(): void
    {
        $today = Carbon::parse('2026-08-22 19:00:00');
        Carbon::setTestNow($today);

        $att = Attendance::create([
            'employee_id' => 'EMP-002',
            'date' => Carbon::today(),
            'check_in' => Carbon::today()->setHour(14)->setMinute(0), // 2:00 PM
            'check_out' => null,
            'status' => 'present',
        ]);

        Attendance::processAutoClockOuts();

        $att->refresh();
        $this->assertNotNull($att->check_out);
        $this->assertEquals('18:30:00', Carbon::parse($att->check_out)->format('H:i:s'));
        $this->assertEquals('absent', $att->status); // 14:00 to 18:30 = 4.5 hours < 5h -> absent
    }

    public function test_employee_clock_out_calculates_working_hours_status(): void
    {
        Employee::create([
            'employee_id' => 'EMP-003',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'designation' => 'Developer',
        ]);

        $testNow = Carbon::parse('2026-08-22 09:00:00');
        Carbon::setTestNow($testNow);

        $this->post(route('employee.lookup'), ['employee_id' => 'EMP-003']);
        $this->post(route('employee.clock-in'), ['employee_id' => 'EMP-003']);

        // Advance to 3:00 PM (6 hours worked)
        Carbon::setTestNow(Carbon::parse('2026-08-22 15:00:00'));

        $this->post(route('employee.clock-out'), ['employee_id' => 'EMP-003'])
            ->assertOk();

        $att = Attendance::where('employee_id', 'EMP-003')->first();
        $this->assertEquals('half-day', $att->status); // 6 hours worked
    }
}
