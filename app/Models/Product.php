<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'category', 'price', 'cost_price', 'pic',
        'opening_stock_ctn', 'opening_stock_pcs',
        'low_stock_threshold_ctn', 'is_active',
    ];

    public function dailySales(): HasMany
    {
        return $this->hasMany(DailySale::class);
    }

    public function liftings(): HasMany
    {
        return $this->hasMany(Lifting::class);
    }
}
