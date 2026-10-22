<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashCreditEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'entry_date', 'sales_rep_id', 'sales_amount', 'collection',
        'credit', 'credit_collection', 'opening_credit_balance',
        'route_credit', 'route_shortage',
    ];

    protected $casts = [
        'entry_date' => 'date',
    ];

    public function salesRep(): BelongsTo
    {
        return $this->belongsTo(SalesRep::class);
    }
}
