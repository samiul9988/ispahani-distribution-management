<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory, Auditable;

    protected $fillable = ['name', 'company', 'representative', 'contact', 'opening_balance', 'is_active'];

    public function liftings(): HasMany
    {
        return $this->hasMany(Lifting::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(SupplierPayment::class);
    }
}
