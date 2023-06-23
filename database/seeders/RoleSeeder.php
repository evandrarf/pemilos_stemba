<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $full_permissions = Permission::get()->pluck('name');

        $data = [
            [
                'name' => 'admin',
                'is_default' => true,
                'permissions' => $full_permissions
            ],
            [
                'name' => 'user',
                'is_default' => true,
                'permissions' => ['view_general_dashboard']
            ]
        ];

        // Create role and assign permission to role
        foreach ($data as $key => $value) {
            try {
                $role = Role::updateOrCreate([
                    'id' => $key + 1
                ], [
                    'id' => $key + 1,
                    'name' => $value["name"],
                    'is_default' => $value["is_default"],
                ]);

                $role->syncPermissions($value['permissions']);
            } catch (\Exception $exception) {
                $this->command->info($exception->getMessage());
                // Do something when the exception
            }
        }
    }
}
