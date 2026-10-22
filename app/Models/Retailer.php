<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Retailer extends Model
{
    use HasFactory, Auditable;

    protected $fillable = ['name', 'shop_name', 'phone', 'area', 'address', 'market_id', 'is_active'];

    public function market(): BelongsTo
    {
        return $this->belongsTo(Market::class);
    }
}
