<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'sales_rep_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function salesRep(): BelongsTo
    {
        return $this->belongsTo(SalesRep::class);
    }

    public function isSalesRep(): bool
    {
        return $this->role === 'sr';
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Admins implicitly have every permission. Other roles only have the
     * permissions explicitly granted to their role in role_permissions.
     */
    public function hasPermission(string $key): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        return RolePermission::query()
            ->where('role', $this->role)
            ->where('permission_key', $key)
            ->exists();
    }
}
