<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Market extends Model
{
    use HasFactory, Auditable;

    protected $fillable = ['name', 'is_active'];

    public function salesReps(): HasMany
    {
        return $this->hasMany(SalesRep::class);
    }
}
