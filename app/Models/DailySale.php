<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailySale extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_date', 'sales_rep_id', 'product_id',
        'loading_ctn', 'loading_pcs', 'total_loaded_pcs',
        'unloading_pcs', 'sales_pcs', 'price_at_sale',
        'total_taka', 'discount', 'net_amount',
    ];

    protected $casts = [
        'sale_date' => 'date',
    ];

    public function salesRep(): BelongsTo
    {
        return $this->belongsTo(SalesRep::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
