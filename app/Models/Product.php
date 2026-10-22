<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'name', 'category', 'category_id', 'price', 'cost_price', 'pic',
        'opening_stock_ctn', 'opening_stock_pcs',
        'low_stock_threshold_ctn', 'is_active',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function dailySales(): HasMany
    {
        return $this->hasMany(DailySale::class);
    }

    public function liftings(): HasMany
    {
        return $this->hasMany(Lifting::class);
    }
}
