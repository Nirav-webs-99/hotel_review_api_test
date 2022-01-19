<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\Hotel;

class HotelTableSeeder extends Seeder
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
                'name' => 'Hyaat',
                'star' => 4.5,
                'address' => '17/A, Ashram Rd, Usmanpura, Ahmedabad, Gujarat 380014',
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            1 => [
                'name' => 'Taj',
                'star' => 5,
                'address' => ' 11th, 1st Floor,Shyam Icon, Sardar Patel Ring Rd, near HP Petrol Pump, Aslali, Ahmedabad, Gujarat 382440',
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            2 => [
                'name' => 'Holiday Inn',
                'star' => 3,
                'address' => 'Plot No.38/1, Prahlad Nagar Rd, Beside Venus Atlantis, Prahlad Nagar, Ahmedabad, Gujarat 380015',
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            3 => [
                'name' => 'Sakar',
                'star' => 2,
                'address' => 'Opposite Town Hall, Nr. Sakar II & IV, Ashram Rd, Ellisbridge, Ahmedabad, Gujarat 380006',
                'active' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        $count = Hotel::count();

        // If no hotel then add
        if ($count == 0) {
            foreach ($data as $hotelData) {

                Hotel::create($hotelData);
            }
        }
    }
}
