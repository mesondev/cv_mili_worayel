<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Support\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RolePermissionController extends Controller
{
    public function edit(int $role): View
    {
        $this->authorizeAction('view');
        $role = Role::with('permissions')->findOrFail($role);
        $permissions = Permission::query()
            ->where('slug', 'like', '%.%')
            ->orderBy('slug')
            ->get()
            ->groupBy(fn (Permission $permission) => str($permission->slug)->before('.')->toString());

        return view('admin.roles.permissions', compact('role', 'permissions'));
    }

    public function update(Request $request, int $role): RedirectResponse
    {
        $this->authorizeAction('update');
        $role = Role::findOrFail($role);
        abort_if($role->slug === 'super-admin', 403, 'Hak akses Super Admin tidak dapat diubah.');

        $data = $request->validate([
            'permissions' => ['sometimes', 'array'],
            'permissions.*' => ['string', 'distinct', 'exists:permissions,slug'],
        ]);
        $permissionSlugs = $data['permissions'] ?? [];
        abort_if(in_array('*', $permissionSlugs, true), 422, 'Hak akses wildcard tidak dapat diberikan.');

        $permissionIds = Permission::whereIn('slug', $permissionSlugs)->pluck('id');
        DB::transaction(fn () => $role->permissions()->sync($permissionIds));
        ActivityLogger::record('Memperbarui hak akses peran', 'role', $role->id, [
            'permission_count' => $permissionIds->count(),
        ]);

        return back()->with('success', 'Hak akses peran berhasil diperbarui.');
    }

    private function authorizeAction(string $action): void
    {
        abort_unless(auth()->user()?->canAccessAdminModule('peran', $action), 403);
    }
}
