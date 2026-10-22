<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashBox extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'cash_date', 'opening_balance', 'total_collection', 'total_credit_collection',
        'total_expense', 'total_supplier_payment', 'adjustment', 'closing_balance', 'note',
    ];

    protected $casts = [
        'cash_date' => 'date',
    ];
}
