<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'employee_id',
        'date',
        'check_in',
        'check_out',
        'status',
        'notes',
        'daily_report',
        'latitude',
        'longitude',
        'location_name',
        'edited_lat',
        'edited_lng',
        'edited_location_name',
        'edited_by',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'check_in' => 'datetime',
            'check_out' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calculate attendance status based on total working hours:
     * - >= 8 hours: 'present' (Full Day)
     * - >= 5 hours and < 8 hours: 'half-day' (Half Day)
     * - < 5 hours: 'absent'
     */
    public static function calculateStatus(?Carbon $checkIn, ?Carbon $checkOut): string
    {
        if (!$checkIn || !$checkOut || $checkOut->lt($checkIn)) {
            return 'absent';
        }

        $hours = $checkIn->diffInMinutes($checkOut) / 60.0;

        if ($hours >= 8.0) {
            return 'present';
        } elseif ($hours >= 5.0) {
            return 'half-day';
        } else {
            return 'absent';
        }
    }

    /**
     * Auto clock out employees at 6:30 PM (18:30:00) if they have not checked out:
     * - For any past date
     * - For today if current time is at or past 18:30:00
     */
    public static function processAutoClockOuts(): void
    {
        $now = Carbon::now();
        $today = Carbon::today();

        $unclosedRecords = self::whereNotNull('check_in')
            ->whereNull('check_out')
            ->get();

        foreach ($unclosedRecords as $record) {
            $recordDate = Carbon::parse($record->date);

            $shouldAutoClockOut = false;
            if ($recordDate->lt($today)) {
                $shouldAutoClockOut = true;
            } elseif ($recordDate->isToday() && $now->format('H:i:s') >= '18:30:00') {
                $shouldAutoClockOut = true;
            }

            if ($shouldAutoClockOut) {
                $autoCheckOut = Carbon::parse($recordDate->toDateString() . ' 18:30:00');
                $checkIn = Carbon::parse($record->check_in);

                if ($autoCheckOut->lt($checkIn)) {
                    $autoCheckOut = $checkIn->copy();
                }

                $status = self::calculateStatus($checkIn, $autoCheckOut);

                $record->update([
                    'check_out' => $autoCheckOut,
                    'status' => $status,
                ]);
            }
        }
    }
}
