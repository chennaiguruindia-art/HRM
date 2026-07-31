<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    protected $fillable = [
        'employee_id', 'name', 'email', 'designation', 'branch_id',
        'shift_start', 'shift_end', 'gender', 'age', 'dob', 'join_date', 'photo', 'status', 'salary', 'paid_leaves', 'blood_group',
    ];

    protected function casts(): array
    {
        return [
            'dob' => 'date',
            'join_date' => 'date',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function salaryCalculations(): HasMany
    {
        return $this->hasMany(SalaryCalculation::class);
    }
}
