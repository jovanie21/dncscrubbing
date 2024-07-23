<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	\Spatie\Permission\Models\Role::create(['name'=>'admin']);
    	\Spatie\Permission\Models\Role::create(['name'=>'user']);
    }
}
