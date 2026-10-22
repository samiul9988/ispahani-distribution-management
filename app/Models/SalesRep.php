<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalesRep extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'phone', 'market_id', 'opening_credit', 'sales_target', 'is_active',
    ];

    public function market(): BelongsTo
    {
        return $this->belongsTo(Market::class);
    }

    public function dailySales(): HasMany
    {
        return $this->hasMany(DailySale::class);
    }

    public function cashCreditEntries(): HasMany
    {
        return $this->hasMany(CashCreditEntry::class);
    }
}
