<?php

namespace App\Models;

/**
 * @property int|null $branch_id
 * @property string $employee_id
 * @property string|null $photo
 * @property string|null $designation
 * @property string|null $status
 * @property string|null $email
 * @property string|null $shift_start
 * @property string|null $shift_end
 * @property string|null $gender
 * @property int|null $paid_leaves
 * @property float|int|string|null $salary
 */
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    protected $fillable = [
        'employee_id', 'name', 'email', 'designation', 'branch_id',
        'shift_start', 'shift_end', 'gender', 'age', 'dob', 'join_date', 'photo', 'status', 'salary', 'paid_leaves', 'blood_group',
        'mobile', 'emergency_contact', 'state', 'city',
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
