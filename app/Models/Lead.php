<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $table = 'leads';

    protected $fillable = [
        'branch_id',
        'daily_plan_id',
        'date',
        'salesperson',
        'company_address',
        'company_details',
        'purpose_of_visit',
        'type_of_service',
        'inspection',
        'quotation',
        'followup1',
        'followup2',
        'followup3',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
