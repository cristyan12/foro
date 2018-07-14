<?php

use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    public function run()
    {
        factory(\App\User::class)->create([
            'email' => 'cristyan12@mail.com',
            'username' => 'cristyan12',
            'first_name' => 'Cristyan',
            'last_name' => 'Valera',
            'role' => 'admin',
        ]);
    }
}
