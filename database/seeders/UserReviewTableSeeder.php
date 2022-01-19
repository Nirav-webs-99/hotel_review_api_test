<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\Review;

class UserReviewTableSeeder extends Seeder
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
                'title' => 'Good Hotel Hyaat',
                'description' => 'Hyaat is a very nice hotel.',
                'user_id' => 1,
                'hotel_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            1 => [
                'title' => 'Good Hotel Hyaat',
                'description' => 'Hyaat is a very nice hotel.',
                'user_id' => 2,
                'hotel_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            2 => [
                'title' => 'Good Hotel Taj',
                'description' => 'Taj is a very nice hotel.',
                'user_id' => 1,
                'hotel_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            3 => [
                'title' => 'Good Hotel Taj',
                'description' => 'Taj is a very nice hotel.',
                'user_id' => 2,
                'hotel_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            4 => [
                'title' => 'Good Hotel Holiday Inn',
                'description' => 'Holiday Inn is a very nice hotel.',
                'user_id' => 2,
                'hotel_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        $count = Review::count();

        // If no Review then add
        if ($count == 0) {
            foreach ($data as $reviewData) {

                Review::create($reviewData);
            }
        }
    }
}
