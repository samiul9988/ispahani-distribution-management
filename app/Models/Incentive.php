<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Incentive extends Model
{
    use HasFactory, Auditable;

    protected $fillable = ['incentive_date', 'sales_rep_id', 'amount', 'reason'];

    protected $casts = [
        'incentive_date' => 'date',
    ];

    public function salesRep(): BelongsTo
    {
        return $this->belongsTo(SalesRep::class);
    }
}
