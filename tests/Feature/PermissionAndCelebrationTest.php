<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Notification;
use App\Models\User;
use App\Services\EmployeeNotificationService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PermissionAndCelebrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_permission_must_be_single_day()
    {
        $employee = Employee::create([
            'employee_id' => 'EMP-TEST1',
            'name' => 'Alice Tester',
            'email' => 'alice@example.com',
            'designation' => 'Developer',
            'status' => 'Active',
        ]);

        $response = $this->postJson(route('employee.leave'), [
            'employee_id' => 'EMP-TEST1',
            'type' => 'Permission',
            'from_date' => '2026-09-03',
            'to_date' => '2026-09-04',
            'reason' => 'Doctor appointment',
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment(['success' => false]);
    }

    public function test_permission_auto_approved_for_first_two_requests_in_month()
    {
        $employee = Employee::create([
            'employee_id' => 'EMP-TEST2',
            'name' => 'Bob Tester',
            'email' => 'bob@example.com',
            'designation' => 'Developer',
            'status' => 'Active',
        ]);

        // 1st Permission
        $resp1 = $this->postJson(route('employee.leave'), [
            'employee_id' => 'EMP-TEST2',
            'type' => 'Permission',
            'from_date' => '2026-09-05',
            'to_date' => '2026-09-05',
            'reason' => 'First 1h permission',
        ]);

        $resp1->assertStatus(200);
        $resp1->assertJson(['success' => true, 'auto_approved' => true]);

        $leave1 = LeaveRequest::where('employee_id', 'EMP-TEST2')->first();
        $this->assertEquals('Approved', $leave1->status);
        $this->assertEquals('Permission', $leave1->type);
        $this->assertEquals(1.0, $leave1->hours);

        // 2nd Permission in same month
        $resp2 = $this->postJson(route('employee.leave'), [
            'employee_id' => 'EMP-TEST2',
            'type' => 'Permission',
            'from_date' => '2026-09-12',
            'to_date' => '2026-09-12',
            'reason' => 'Second 1h permission',
        ]);

        $resp2->assertStatus(200);
        $resp2->assertJson(['success' => true, 'auto_approved' => true]);

        $this->assertEquals(2, LeaveRequest::where('employee_id', 'EMP-TEST2')->where('status', 'Approved')->count());
    }

    public function test_permission_exceeding_two_hours_is_disabled()
    {
        $employee = Employee::create([
            'employee_id' => 'EMP-TEST3',
            'name' => 'Charlie Tester',
            'email' => 'charlie@example.com',
            'designation' => 'Designer',
            'status' => 'Active',
        ]);

        // Seed 2 approved permissions in the month
        LeaveRequest::create([
            'employee_id' => 'EMP-TEST3',
            'type' => 'Permission',
            'from_date' => '2026-09-01',
            'to_date' => '2026-09-01',
            'hours' => 1.0,
            'status' => 'Approved',
        ]);
        LeaveRequest::create([
            'employee_id' => 'EMP-TEST3',
            'type' => 'Permission',
            'from_date' => '2026-09-02',
            'to_date' => '2026-09-02',
            'hours' => 1.0,
            'status' => 'Approved',
        ]);

        // 3rd Permission in same month -> Permission is disabled
        $resp3 = $this->postJson(route('employee.leave'), [
            'employee_id' => 'EMP-TEST3',
            'type' => 'Permission',
            'from_date' => '2026-09-15',
            'to_date' => '2026-09-15',
            'reason' => 'Third permission',
        ]);

        $resp3->assertStatus(422);
        $resp3->assertJson([
            'success' => false,
            'permission_disabled' => true,
        ]);

        // However, employee can still submit a Half Day Leave or regular leave
        $respLeave = $this->postJson(route('employee.leave'), [
            'employee_id' => 'EMP-TEST3',
            'type' => 'Half Day Leave',
            'from_date' => '2026-09-15',
            'to_date' => '2026-09-15',
            'reason' => 'Half day leave after permission limit',
        ]);

        $respLeave->assertStatus(200);
        $respLeave->assertJson(['success' => true]);
    }

    public function test_work_anniversary_and_birthday_notifications_generation()
    {
        Carbon::setTestNow(Carbon::parse('2026-09-03'));

        $emp1 = Employee::create([
            'employee_id' => 'EMP-ANNIV',
            'name' => 'David Anniversary',
            'email' => 'david@example.com',
            'designation' => 'Lead Engineer',
            'join_date' => '2025-09-03', // 1 year ago today
            'dob' => '1995-09-03',       // Birthday today
            'status' => 'Active',
        ]);

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        EmployeeNotificationService::checkDailyAnniversariesAndBirthdays();

        // Check Employee received anniversary and birthday notifications
        $annivNotif = Notification::where('employee_id', 'EMP-ANNIV')
            ->where('type', 'bi-award-fill')
            ->first();
        $this->assertNotNull($annivNotif);
        $this->assertStringContainsString('1st Work Anniversary', $annivNotif->title);
        $this->assertStringContainsString('1 year', $annivNotif->body);

        $bdayNotif = Notification::where('employee_id', 'EMP-ANNIV')
            ->where('type', 'bi-cake2-fill')
            ->first();
        $this->assertNotNull($bdayNotif);
        $this->assertStringContainsString('Happy Birthday, David Anniversary!', $bdayNotif->title);

        // Check Admin also notified
        $adminAnniv = Notification::where('user_id', $admin->id)
            ->where('type', 'bi-award-fill')
            ->first();
        $this->assertNotNull($adminAnniv);

        $adminBday = Notification::where('user_id', $admin->id)
            ->where('type', 'bi-cake2-fill')
            ->first();
        $this->assertNotNull($adminBday);

        // Verify duplicate prevention on second run
        $totalNotifsBefore = Notification::count();
        EmployeeNotificationService::checkDailyAnniversariesAndBirthdays();
        $totalNotifsAfter = Notification::count();
        $this->assertEquals($totalNotifsBefore, $totalNotifsAfter);

        Carbon::setTestNow();
    }
}
