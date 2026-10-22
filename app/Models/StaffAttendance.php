<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffAttendance extends Model
{
    use HasFactory, Auditable;

    protected $fillable = ['attendance_date', 'staff_id', 'status'];

    protected $casts = [
        'attendance_date' => 'date',
    ];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }
}
