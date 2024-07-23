<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $user=\App\Models\User::create([
        'name'=>'admin',
        'email'=>'admin@gmail.com',
        'password'=>bcrypt('123456'),
        'actual_password'=>'123456'
       ]);
       $user->assignRole('admin');
    }
}
