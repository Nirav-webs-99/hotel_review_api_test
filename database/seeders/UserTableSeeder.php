<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            0 => [
                'name' => 'Nirav',
                'email' => 'nirav.abc@xyz.com',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            1 => [
                'name' => 'Parth',
                'email' => 'parth.abc@xyz.com',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            2 => [
                'name' => 'Mitesh',
                'email' => 'mitesh.abc@xyz.com',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        $count = User::count();

        // If no user then add
        if ($count == 0) {

            foreach ($data as $userData) {
                User::create($userData);
            }
        }
    }
}
