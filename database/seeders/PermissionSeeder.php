<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Seed the permissions table from the canonical registry.
     */
    public function run(): void
    {
        foreach (Permission::registry() as $group => $permissions) {
            foreach ($permissions as $key => $label) {
                Permission::query()->updateOrCreate(
                    ['key' => $key],
                    ['label' => $label, 'group' => $group],
                );
            }
        }
    }
}
