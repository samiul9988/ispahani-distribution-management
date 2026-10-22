<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DamagedStock extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'damage_date', 'product_id', 'damage_sending_id', 'qty_pcs', 'value', 'reason', 'status',
    ];

    protected $casts = [
        'damage_date' => 'date',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function damageSending(): BelongsTo
    {
        return $this->belongsTo(DamageSending::class);
    }
}
