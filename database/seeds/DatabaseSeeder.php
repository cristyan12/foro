<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminTableSeeder::class);
        $this->call(UsersTableSeeder::class);

        $this->call(CategoryTableSeeder::class);

        $this->call(PostTableSeeder::class);
        
        $this->call(CommentTableSeeder::class);
    }
}
