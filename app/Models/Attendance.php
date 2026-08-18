<?php

namespace App\Models;

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
}
