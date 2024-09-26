<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Backpack\PermissionManager\app\Models\Permission;
use Backpack\PermissionManager\app\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database Permission seed.

     * Permissions are fixed in code and are seeded here.
     * use 'php artisan db:seed --class=PermissionSeeder --force' in production
     *
     * @return void
     */
    public function run()
    {
        // create permission for each combination of table.level
        collect([ // tables
            'users',
            'roles',
        ])
            ->crossJoin([ // levels
                'see',
                'edit',
            ])
            ->each(
                fn (array $item) => Permission::firstOrCreate([
                    'name' => implode('.', $item),
                ])
                    ->save()
            )
            //
        ;
        User::first()
            ->givePermissionTo(['users.edit']);
    }
}
