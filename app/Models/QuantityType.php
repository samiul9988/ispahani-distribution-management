<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuantityType extends Model
{
    protected $fillable = ['value', 'source', 'is_default'];
}
