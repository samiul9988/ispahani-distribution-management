<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\RolePermission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RolePermissionController extends Controller
{
    /**
     * Roles that can be granted permissions. Admin always has full access
     * and is intentionally excluded from the matrix.
     *
     * @var array<int, string>
     */
    private const ASSIGNABLE_ROLES = ['manager', 'staff', 'sr'];

    public function edit(): View
    {
        $groups = Permission::registry();

        $granted = RolePermission::query()
            ->whereIn('role', self::ASSIGNABLE_ROLES)
            ->get()
            ->groupBy('role')
            ->map(fn ($rows) => $rows->pluck('permission_key')->all());

        return view('role_permissions.edit', [
            'groups' => $groups,
            'roles' => self::ASSIGNABLE_ROLES,
            'granted' => $granted,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validKeys = Permission::keys();

        $data = $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['array'],
            'permissions.*.*' => ['string', 'in:'.implode(',', $validKeys)],
        ]);

        $selected = $data['permissions'] ?? [];

        foreach (self::ASSIGNABLE_ROLES as $role) {
            $keys = array_values(array_intersect($selected[$role] ?? [], $validKeys));

            RolePermission::query()->where('role', $role)->delete();

            foreach ($keys as $key) {
                RolePermission::query()->create([
                    'role' => $role,
                    'permission_key' => $key,
                ]);
            }
        }

        return redirect()->route('role-permissions.edit')->with('success', 'Permissions updated.');
    }
}
