<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierPayment extends Model
{
    use HasFactory;

    protected $fillable = ['payment_date', 'amount', 'method', 'note'];

    protected $casts = [
        'payment_date' => 'date',
    ];
}
