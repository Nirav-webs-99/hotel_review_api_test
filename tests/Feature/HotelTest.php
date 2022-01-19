<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Hotel;
use App\Models\User;
use App\Models\Review;
use Carbon\Carbon;

class HotelTest extends TestCase
{
    /**
     * A feature test to get active hotel Data based on hotel id
     *
     * @return void
     */
    public function test_get_active_hotel_by_id()
    {
        $hotel_id = Hotel::where('active', 1)->get()->random()->id;
        $response = $this->get('/api/hotel/' . $hotel_id)
            ->assertStatus(200)
            ->assertJsonStructure(
                [
                    'code',
                    'message',
                    'data' => [
                        'id',
                        'name',
                        'star',
                        'review' => [
                            '*' => [
                                "id",
                                "title",
                                "description",
                                "author",
                                "create_at",
                                "update_at"
                            ],
                        ]
                    ],
                ]
            );
    }


    /**
     * A feature test to get all active hotel Data 
     *
     * @return void
     */
    public function test_get_all_active_hotels()
    {
        $response = $this->get('/api/hotels')
            ->assertStatus(200)
            ->assertJsonStructure(
                [
                    'code',
                    'message',
                    'data' =>  [
                        '*' => [
                            "id",
                            "name",
                            "address",
                            "star",
                            "create_at",
                            "update_at",
                            "active",
                            "review" => [
                                '*' => [
                                    "id",
                                    "title",
                                    "description",
                                    "author",
                                    "create_at",
                                    "update_at"
                                ],
                            ],
                        ],
                    ],
                ]
            );
    }

    /**
     * A feature test to get inactive hotel Data based on hotel id
     *
     * @return void
     */
    public function test_for_get_inactive_hotel_by_id()
    {
        $hotel_id = Hotel::where('active', 0)->get()->random()->id;
        $response = $this->get('/api/hotel/' . $hotel_id)
            ->assertStatus(200)
            ->assertJsonStructure(
                [
                    'code',
                    'message',
                ]
            );
    }

    /**
     * A feature test to store new review 
     *
     * @return void
     */
    public function test_for_add_hotel_review()
    {
        $user = User::create([
            'name' => rand(),
            'email' => rand() . '.abc@xyz.com',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        //$user = User::create($userData);
        $hotel = Hotel::create([
            'name' => rand(),
            'star' => 2,
            'address' => 'Opposite Town Hall, Nr. Sakar II & IV, Ashram Rd, Ellisbridge, Ahmedabad, Gujarat 380006',
            'active' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $payload = [
            "hotel_id" => $hotel->id,
            "user_id" => $user->id,
            "review_title" => "test",
            "review_data" => "test description"
        ];

        $this->json('POST', 'api/save-hotel-review', $payload)
            ->assertStatus(200)
            ->assertJson([
                'code' => '200',
                'message' => 'Hotel Review saved.',
            ]);
    }

    /**
     * A feature test to update review based on review id 
     *
     * @return void
     */
    public function test_for_update_hotel_review()
    {
        $user = User::create([
            'name' => rand(),
            'email' => rand() . '.abc@xyz.com',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        //$user = User::create($userData);
        $hotel = Hotel::create([
            'name' => rand(),
            'star' => 2,
            'address' => 'Opposite Town Hall, Nr. Sakar II & IV, Ashram Rd, Ellisbridge, Ahmedabad, Gujarat 380006',
            'active' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $hotelReview = Review::create([
            'title' => 'Good Hotel HollywoodInn',
            'description' => 'HollywoodInn is a very nice hotel.',
            'user_id' => $user->id,
            'hotel_id' => $hotel->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $payload = [
            "hotel_id" => $hotel->id,
            "user_id" => $user->id,
            "review_title" => "test",
            "review_data" => "test description"
        ];

        $this->json('PUT', 'api/update-hotel-review/' . $hotelReview->id, $payload)
            ->assertStatus(200)
            ->assertJson([
                'code' => '200',
                'message' => 'Hotel Review updated.',
            ]);
    }

    /**
     * A feature test to delete hotel review data
     *
     * @return void
     */
    public function test_for_delete_hotel_review()
    {
        $user = User::create([
            'name' => rand(),
            'email' => rand() . '.abc@xyz.com',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        
        $hotel = Hotel::create([
            'name' => rand(),
            'star' => 2,
            'address' => 'Opposite Town Hall, Nr. Sakar II & IV, Ashram Rd, Ellisbridge, Ahmedabad, Gujarat 380006',
            'active' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $hotelReview = Review::create([
            'title' => 'Good Hotel HollywoodInn',
            'description' => 'HollywoodInn is a very nice hotel.',
            'user_id' => $user->id,
            'hotel_id' => $hotel->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $this->json('DELETE', 'api/review/' . $hotelReview->id)
            ->assertStatus(200)
            ->assertJson([
                'code' => '200',
                'message' => 'Hotel Review deleted successfully.',
            ]);

    }

    /**
     * A feature test to store new review required data
     *
     * @return void
     */
    public function test_for_add_hotel_review_required_fields()
    {
        $this->json('POST', 'api/save-hotel-review')
            ->assertStatus(200)
            ->assertJson([
                'code' => '401',
                'message' => 'The hotel id field is required,The user id field is required,The review title field is required,The review data field is required',
            ]);
    }
    /**
     * A feature test to update review required data
     *
     * @return void
     */
    public function test_for_update_hotel_review_required_fields()
    {
        $user = User::create([
            'name' => rand(),
            'email' => rand() . '.abc@xyz.com',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        //$user = User::create($userData);
        $hotel = Hotel::create([
            'name' => rand(),
            'star' => 2,
            'address' => 'Opposite Town Hall, Nr. Sakar II & IV, Ashram Rd, Ellisbridge, Ahmedabad, Gujarat 380006',
            'active' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        //$hotel = User::create($hotelData);
        $hotelReview = Review::create([
            'title' => 'Good Hotel HollywoodInn',
            'description' => 'HollywoodInn is a very nice hotel.',
            'user_id' => $user->id,
            'hotel_id' => $hotel->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $this->json('PUT', 'api/update-hotel-review/' . $hotelReview->id)
            ->assertStatus(200)
            ->assertJson([
                'code' => '401',
                'message' => 'The hotel id field is required,The user id field is required,The review title field is required,The review data field is required',
            ]);
    }

    /**
     * A feature test to update review that not exist
     *
     * @return void
     */
    public function test_for_update_hotel_review_that_not_exist()
    {
        //reviw id that not exist in database
        $reviewId = random_int(100000, 999999);
        $payload = [
            "hotel_id" => random_int(100, 999),
            "user_id" => random_int(100, 999),
            "review_title" => "test",
            "review_data" => "test description"
        ];
        $this->json('PUT', 'api/update-hotel-review/' . $reviewId, $payload)
            ->assertStatus(200)
            ->assertJson([
                'code' => '401',
                'message' => 'Invalid Hotel id or User Id or Review Id',
            ]);
    }

    /**
     * A feature test to delete review that is not exist
     *
     * @return void
     */
    public function test_for_delete_review_that_not_exist()
    {
        //reviw id that not exist in database
        $reviewId = random_int(100000, 999999);

        $this->json('DELETE', 'api/review/' . $reviewId)
            ->assertStatus(200)
            ->assertJson([
                'code' => '401',
                'message' => 'Review not found, Please try again',
            ]);
    }
}
