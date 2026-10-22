<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackType extends Model
{
    protected $fillable = ['value', 'source', 'is_default'];
}
