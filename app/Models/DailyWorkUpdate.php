<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyWorkUpdate extends Model
{
    protected $fillable = [
        'employee_id',
        'date',
        'report',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }
}
