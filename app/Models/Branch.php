<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Branch extends Model
{
    protected $fillable = ['name', 'location', 'manager', 'phone'];

    public function slug(): string
    {
        return Str::slug($this->name);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
