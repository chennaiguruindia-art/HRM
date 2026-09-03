<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MarkAbsentEmployees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:mark-absent-employees';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark absent employees who did not clock in today';

    public function handle()
    {
        \App\Models\Attendance::processAutoClockOuts();

        $today = \Carbon\Carbon::today();
        $employees = \App\Models\Employee::where('status', 'Active')->get();

        $count = 0;
        foreach ($employees as $employee) {
            $exists = \App\Models\Attendance::where('employee_id', $employee->employee_id)
                ->whereDate('date', $today)
                ->exists();

            if (!$exists) {
                \App\Models\Attendance::create([
                    'employee_id' => $employee->employee_id,
                    'date' => $today,
                    'status' => 'absent',
                ]);
                $count++;
            }
        }

        \App\Services\EmployeeNotificationService::checkDailyAnniversariesAndBirthdays();

        $this->info("Marked {$count} employees as absent for {$today->toDateString()}");
    }
}
