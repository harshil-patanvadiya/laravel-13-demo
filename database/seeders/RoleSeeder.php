<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Seed the application's roles.
     */
    public function run(): void
    {
        Role::firstOrCreate([
            'name' => config('site.roles.admin'),
            'guard_name' => 'api',
        ]);

        Role::firstOrCreate([
            'name' => config('site.roles.user'),
            'guard_name' => 'api',
        ]);
    }
}
