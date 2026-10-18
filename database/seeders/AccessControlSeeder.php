<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class AccessControlSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'super-admin' => ['Super Admin', 'Akses penuh ke seluruh modul dan tindakan.'],
            'admin' => ['Admin', 'Mengelola operasional website yang diizinkan.'],
            'editor' => ['Editor', 'Mengelola berita dan metadata website.'],
            'hrd' => ['HRD', 'Mengelola lowongan, lamaran, dan data pegawai.'],
            'marketing' => ['Marketing', 'Mengelola konten pemasaran dan pesan masuk.'],
        ];

        foreach ($roles as $slug => [$name, $description]) {
            Role::firstOrCreate(['slug' => $slug], [
                'name' => $name,
                'description' => $description,
            ]);
        }

        $permissionsByRole = config('admin.permissions');
        $allPermissionSlugs = [];
        foreach ($permissionsByRole as $rolePermissions) {
            foreach ($rolePermissions as $module => $actions) {
                if ($module === '*') {
                    continue;
                }

                foreach ($actions as $action) {
                    $allPermissionSlugs[] = $module.'.'.$action;
                }
            }
        }
        $allPermissionSlugs = array_values(array_unique($allPermissionSlugs));
        $permissionIds = collect($allPermissionSlugs)->mapWithKeys(function (string $slug) {
            $wasCreated = ! Permission::where('slug', $slug)->exists();
            $permission = Permission::firstOrCreate(['slug' => $slug], [
                'name' => str($slug)->replace('.', ' ')->headline(),
                'description' => 'Izin '.$slug,
            ]);

            return [$slug => ['id' => $permission->id, 'created' => $wasCreated]];
        });

        foreach ($permissionsByRole as $roleSlug => $rolePermissions) {
            $role = Role::where('slug', $roleSlug)->firstOrFail();
            $deleteCareerPermission = Permission::where('slug', 'karir.delete')->value('id');
            if ($roleSlug === 'hrd' && $deleteCareerPermission) {
                $role->permissions()->detach($deleteCareerPermission);
            }

            $slugs = isset($rolePermissions['*'])
                ? $allPermissionSlugs
                : collect($rolePermissions)->flatMap(function (array $actions, string $module) {
                    return collect($actions)->map(fn (string $action) => $module.'.'.$action);
                })->all();

            if ($role->permissions()->exists()) {
                $slugs = array_values(array_filter(
                    $slugs,
                    fn (string $slug) => $permissionIds[$slug]['created']
                ));
            }

            $role->permissions()->syncWithoutDetaching(
                collect($slugs)->map(fn (string $slug) => $permissionIds[$slug]['id'])->all()
            );
        }
    }
}
