<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory, Auditable;

    protected $fillable = ['payment_date', 'direction', 'party_name', 'amount', 'method', 'note'];

    protected $casts = [
        'payment_date' => 'date',
    ];
}
