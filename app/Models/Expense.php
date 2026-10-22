<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory, Auditable;

    protected $fillable = ['expense_date', 'category', 'description', 'amount'];

    protected $casts = [
        'expense_date' => 'date',
    ];
}
