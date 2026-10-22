<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DamageSending extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'sending_date', 'supplier_id', 'total_qty_pcs', 'total_value', 'status', 'note',
    ];

    protected $casts = [
        'sending_date' => 'date',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function damagedStocks(): HasMany
    {
        return $this->hasMany(DamagedStock::class);
    }
}
