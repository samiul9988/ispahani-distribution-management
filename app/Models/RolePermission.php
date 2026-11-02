<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['role', 'permission_key'])]
class RolePermission extends Model
{
    //
}
