<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        $users =  [
            [
                'name' => 'Johan Smith',
                'email' => 'abc@gmail.com',
                'password' => bcrypt('123456'),
            ],
            [
                'name' => 'Johan Mixed',
                'email' => 'assd@gmail.com',
                'password' => bcrypt('123456'),
            ],
        ];
        foreach ($users as $key => $value) {
            User::create($value);
        }

    }
}
