<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['Admin', 'Merchant', 'Outlet'];

        for($index=0; $index<count($roles); $index++) {
            Role::create(['name' => $roles[$index]]);
        }
    }
}
