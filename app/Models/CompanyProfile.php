<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'distributor_name', 'supplier_name', 'address', 'phone', 'email', 'supplier_due',
    ];
}
