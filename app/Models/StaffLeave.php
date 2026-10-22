<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffLeave extends Model
{
    use HasFactory, Auditable;

    protected $fillable = ['staff_id', 'from_date', 'to_date', 'reason', 'status'];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
    ];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }
}
