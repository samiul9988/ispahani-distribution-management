<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FreeOffer extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'product_id', 'type', 'buy_qty', 'free_qty', 'discount_amount', 'starts_at', 'ends_at', 'is_active',
    ];

    protected $casts = [
        'starts_at' => 'date',
        'ends_at' => 'date',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeActiveOn(Builder $query, string $date): Builder
    {
        return $query->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', $date))
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', $date));
    }

    /**
     * Compute the discount (Taka) this offer grants for a given sold quantity (pcs) at a given price.
     */
    public function calculateDiscount(float $soldPcs, float $price): float
    {
        if ($soldPcs <= 0 || $this->buy_qty <= 0) {
            return 0;
        }

        $triggers = floor($soldPcs / $this->buy_qty);
        if ($triggers <= 0) {
            return 0;
        }

        if ($this->type === 'free_qty') {
            return $triggers * (float) $this->free_qty * $price;
        }

        return $triggers * (float) $this->discount_amount;
    }
}
