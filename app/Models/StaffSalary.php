<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffSalary extends Model
{
    use HasFactory, Auditable;

    protected $fillable = ['staff_id', 'salary_month', 'amount', 'paid_date', 'status'];

    protected $casts = [
        'salary_month' => 'date',
        'paid_date' => 'date',
    ];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }
}
