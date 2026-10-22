<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Staff extends Model
{
    use HasFactory, Auditable;

    protected $fillable = ['name', 'designation', 'phone', 'join_date', 'monthly_salary', 'is_active'];

    protected $casts = [
        'join_date' => 'date',
    ];

    public function attendances(): HasMany
    {
        return $this->hasMany(StaffAttendance::class);
    }

    public function leaves(): HasMany
    {
        return $this->hasMany(StaffLeave::class);
    }

    public function salaries(): HasMany
    {
        return $this->hasMany(StaffSalary::class);
    }
}
