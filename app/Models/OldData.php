<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OldData extends Model
{
    protected $table = 'old_data';

    protected $fillable = [
        'branch_id',
        'sn',
        'staff_name',
        'entry_date',
        'entry_time',
        'work_name',
        'units',
        'description',
        'location',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
