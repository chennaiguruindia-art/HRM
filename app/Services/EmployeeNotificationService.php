<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

class EmployeeNotificationService
{
    /**
     * Check and generate work anniversary and birthday notifications for all active employees.
     */
    public static function checkDailyAnniversariesAndBirthdays(): void
    {
        $today = Carbon::today();
        $employees = Employee::where('status', 'Active')->get();
        $admins = User::where('role', 'admin')->get();

        foreach ($employees as $employee) {
            $user = User::where('email', $employee->email)->first();

            // 1. Check Work Anniversary
            if ($employee->join_date) {
                $joinDate = Carbon::parse($employee->join_date);
                if ($joinDate->format('m-d') === $today->format('m-d')) {
                    $years = $today->year - $joinDate->year;
                    if ($years >= 1) {
                        $alreadySent = Notification::where(function ($q) use ($employee, $user) {
                            $q->where('employee_id', $employee->employee_id);
                            if ($user) {
                                $q->orWhere('user_id', $user->id);
                            }
                        })
                        ->where('type', 'bi-award-fill')
                        ->whereYear('created_at', $today->year)
                        ->exists();

                        if (!$alreadySent) {
                            $suffix = self::getOrdinalSuffix($years);
                            $anniversaryTitle = "🎉 Happy {$years}{$suffix} Work Anniversary!";
                            $anniversaryBody = "Congratulations {$employee->name}! You have successfully completed {$years} " . ($years === 1 ? 'year' : 'years') . " of dedicated service with us. Wishing you continued success, growth, and joy in your journey ahead!";

                            // Send to employee
                            Notification::create([
                                'user_id' => $user?->id,
                                'employee_id' => $employee->employee_id,
                                'title' => $anniversaryTitle,
                                'body' => $anniversaryBody,
                                'type' => 'bi-award-fill',
                                'is_read' => false,
                            ]);

                            // Notify Admins
                            foreach ($admins as $admin) {
                                $adminAnnivSent = Notification::where('user_id', $admin->id)
                                    ->where('title', 'LIKE', "%{$employee->name}%Anniversary%")
                                    ->whereYear('created_at', $today->year)
                                    ->exists();

                                if (!$adminAnnivSent) {
                                    Notification::create([
                                        'user_id' => $admin->id,
                                        'employee_id' => $admin->employee_id ?? null,
                                        'title' => "🎉 {$employee->name}'s {$years}{$suffix} Work Anniversary",
                                        'body' => "Today is {$employee->name}'s {$years}{$suffix} work anniversary ({$years} " . ($years === 1 ? 'year' : 'years') . " completed). Congratulate them on this milestone!",
                                        'type' => 'bi-award-fill',
                                        'is_read' => false,
                                    ]);
                                }
                            }
                        }
                    }
                }
            }

            // 2. Check Birthday
            if ($employee->dob) {
                $dob = Carbon::parse($employee->dob);
                if ($dob->format('m-d') === $today->format('m-d')) {
                    $alreadySent = Notification::where(function ($q) use ($employee, $user) {
                        $q->where('employee_id', $employee->employee_id);
                        if ($user) {
                            $q->orWhere('user_id', $user->id);
                        }
                    })
                    ->where('type', 'bi-cake2-fill')
                    ->whereYear('created_at', $today->year)
                    ->exists();

                    if (!$alreadySent) {
                        $bdayTitle = "🎂 Happy Birthday, {$employee->name}!";
                        $bdayBody = "Wishing you a very Happy Birthday, {$employee->name}! May your day be filled with wonderful moments, good health, happiness, and great success in the year ahead!";

                        // Send to employee
                        Notification::create([
                            'user_id' => $user?->id,
                            'employee_id' => $employee->employee_id,
                            'title' => $bdayTitle,
                            'body' => $bdayBody,
                            'type' => 'bi-cake2-fill',
                            'is_read' => false,
                        ]);

                        // Notify Admins
                        foreach ($admins as $admin) {
                            $adminBdaySent = Notification::where('user_id', $admin->id)
                                ->where('title', 'LIKE', "%{$employee->name}%Birthday%")
                                ->whereYear('created_at', $today->year)
                                ->exists();

                            if (!$adminBdaySent) {
                                Notification::create([
                                    'user_id' => $admin->id,
                                    'employee_id' => $admin->employee_id ?? null,
                                    'title' => "🎂 Today is {$employee->name}'s Birthday!",
                                    'body' => "Today is {$employee->name}'s birthday! Wish them a wonderful day!",
                                    'type' => 'bi-cake2-fill',
                                    'is_read' => false,
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Get celebration status for a specific employee today (for dashboard banner display).
     */
    public static function getCelebrationsForEmployee(Employee $employee): array
    {
        $today = Carbon::today();
        $isBirthday = false;
        $birthdayMsg = '';
        $isAnniversary = false;
        $anniversaryYears = 0;
        $anniversaryMsg = '';

        if ($employee->dob) {
            $dob = Carbon::parse($employee->dob);
            if ($dob->format('m-d') === $today->format('m-d')) {
                $isBirthday = true;
                $birthdayMsg = "🎂 Wishing you a very Happy Birthday, {$employee->name}! Have a wonderful day!";
            }
        }

        if ($employee->join_date) {
            $joinDate = Carbon::parse($employee->join_date);
            if ($joinDate->format('m-d') === $today->format('m-d')) {
                $years = $today->year - $joinDate->year;
                if ($years >= 1) {
                    $suffix = self::getOrdinalSuffix($years);
                    $isAnniversary = true;
                    $anniversaryYears = $years;
                    $anniversaryMsg = "🎉 Congratulations on completing {$years} " . ($years === 1 ? 'year' : 'years') . " with us! Happy {$years}{$suffix} Work Anniversary!";
                }
            }
        }

        return [
            'is_birthday' => $isBirthday,
            'birthday_msg' => $birthdayMsg,
            'is_anniversary' => $isAnniversary,
            'anniversary_years' => $anniversaryYears,
            'anniversary_msg' => $anniversaryMsg,
        ];
    }

    private static function getOrdinalSuffix(int $number): string
    {
        if (!in_array(($number % 100), [11, 12, 13])) {
            switch ($number % 10) {
                case 1: return 'st';
                case 2: return 'nd';
                case 3: return 'rd';
            }
        }
        return 'th';
    }
}
