<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lifting extends Model
{
    use HasFactory;

    protected $fillable = [
        'lifting_date', 'product_id', 'lifting_ctn', 'lifting_pcs', 'total_lifted_pcs', 'value',
    ];

    protected $casts = [
        'lifting_date' => 'date',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
