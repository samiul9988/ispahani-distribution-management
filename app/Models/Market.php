<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Market extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'is_active'];

    public function salesReps(): HasMany
    {
        return $this->hasMany(SalesRep::class);
    }
}
