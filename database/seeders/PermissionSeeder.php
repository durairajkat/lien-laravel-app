<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;


class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = config('permissions');
        foreach ($permissions as $module => $actions) {
            foreach ($actions as $action) {

                Permission::updateOrCreate(
                    [
                        'name'   => "{$module}.{$action}",
                    ],
                    [
                        'module' => $module,
                    ]
                );
            }
        }
    }
}
