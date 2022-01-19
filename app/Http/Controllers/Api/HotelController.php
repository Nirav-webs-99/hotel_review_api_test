<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\CommonHelper;
use Validator;
use App\Models\Hotel;
use App\Models\User;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class HotelController extends Controller
{
    /**
     * Used for get the all Active Hotel data
     * 
     * @param  \Illuminate\Http\Request  $request 
     * @return Illuminate\Http\JsonResponse
     */
    public function getAllHotelData(Request $request): JsonResponse
    {
        // fetch all active hotel data with review & author data
        $hotelData = Hotel::with(['reviewget', 'reviewget.userget'])->where('active', 1)->get();

        if (!empty($hotelData->count())) {
            foreach ($hotelData as $key => $hotel) {
                $data[$key] = [
                    'id' => $hotel->id,
                    'name' => $hotel->name,
                    'address' => $hotel->address,
                    'star' => (int)$hotel->star,
                    'create_at' => Carbon::createFromFormat('Y-m-d H:i:s', $hotel->created_at)->format('d/M/Y H:i:s'),
                    'update_at' => Carbon::createFromFormat('Y-m-d H:i:s', $hotel->updated_at)->format('d/M/Y H:i:s'),
                    'active' => $hotel->active == 1 ? 'Active' : '',
                ];
                if (!empty($hotel->reviewget->count())) {
                    foreach ($hotel->reviewget as $review) {
                        $data[$key]['review'][] = [
                            'id' => $review->id,
                            'title' => $review->title,
                            'description' => $review->description,
                            'author' => $review->userget->name,
                            'create_at' => Carbon::createFromFormat('Y-m-d H:i:s', $review->created_at)->format('d/M/Y H:i:s'),
                            'update_at' => Carbon::createFromFormat('Y-m-d H:i:s', $review->updated_at)->format('d/M/Y H:i:s'),
                        ];
                    }
                } else {
                    $data[$key]['review'] = '';
                }
            }
            return response()->json([
                'code' => SUCCESS,
                'message' => HOTELDATAMSG,
                'data' => $data
            ]);
        } else {
            return response()->json([
                'code' => FAILED,
                'message' => HOTELDATANOTFOUNDMSG
            ]);
        }
    }

    /**
     * Used for get the Active Hotel data based on hotel Id 
     * 
     * @param  \Illuminate\Http\Request  $request 
     * @param  int $hotel_id 
     * @return Illuminate\Http\JsonResponse
     */
    public function getHotelDataById(Request $request, int $hotel_id): JsonResponse
    {
        // validate the hotel_id
        /*$validator = Validator::make($request->all(), [
            'hotel_id' => 'required|numeric'
        ]);*/

        // if empty hotel id then return error 
        if (empty($hotel_id)) {
            $customError = CommonHelper::customErrorResponse($validator->messages()->get('*'));
            return response()->json([
                'code' => VALIDATIONERROR,
                'message' => $customError
            ]);
        }

        // fetch active hotel data with review & author data
        $hotelData = Hotel::with(['reviewget', 'reviewget.userget'])->where('active', 1)->find($hotel_id);

        if (!empty($hotelData)) {
            $data = [];
            $data['id'] = $hotelData->id;
            $data['name'] = $hotelData->name;
            $data['star'] = (int)$hotelData->star;

            if (!empty($hotelData->reviewget->count())) {
                foreach ($hotelData->reviewget as $review) {
                    $data['review'][] = [
                        'id' => $review->id,
                        'title' => $review->title,
                        'description' => $review->description,
                        'author' => $review->userget->name,
                        'create_at' => Carbon::createFromFormat('Y-m-d H:i:s', $review->created_at)->format('d/M/Y H:i:s'),
                        'update_at' => Carbon::createFromFormat('Y-m-d H:i:s', $review->updated_at)->format('d/M/Y H:i:s'),
                    ];
                }
            } else {
                $data['review'] = '';
            }
            return response()->json([
                'code' => SUCCESS,
                'message' => HOTELDATAMSG,
                'data' => $data
            ]);
        } else {
            return response()->json([
                'code' => FAILED,
                'message' => HOTELDATANOTFOUNDMSG
            ]);
        }
    }


    /**
     * Used for store all hotel review
     * 
     * @param  \Illuminate\Http\Request  $request 
     * @return Illuminate\Http\JsonResponse
     */
    public function storeHotelReviewData(Request $request): JsonResponse
    {
        // validate the hotel_id, user_id, review title & review description
        $validator = Validator::make($request->all(), [
            'hotel_id' => 'required|numeric',
            'user_id' => 'required|numeric',
            'review_title' => 'required|max:255',
            'review_data' => 'required|max:20000',
        ]);

        // found any error 
        if ($validator->fails()) {
            $customError = CommonHelper::customErrorResponse($validator->messages()->get('*'));
            return response()->json([
                'code' => VALIDATIONERROR,
                'message' => $customError
            ]);
        }

        $hoteDataExist = Hotel::find($request->hotel_id);
        $userDataExist = User::find($request->user_id);
        //found the valid user & hotel data
        if (!empty($hoteDataExist) && !empty($userDataExist)) {
            if (!empty($request->review_id)) {
                $reviewdata = Review::find($request->review_id);
            } else {
                $reviewdata = new Review();
            }
            $reviewdata->title = $request->review_title;
            $reviewdata->description = $request->review_data;
            $reviewdata->user_id = $request->user_id;
            $reviewdata->hotel_id = $request->hotel_id;
            $reviewdata->save();
            if ($reviewdata->id > 0) {
                return response()->json([
                    'code' => SUCCESS,
                    'message' => HOTELREVIEWSAVEDMSG,
                ]);
            } else {
                // error
                $customError = "Review not stored, Please try again";
                return response()->json([
                    'code' => VALIDATIONERROR,
                    'message' => $customError
                ]);
            }
        } else {
            // error
            $customError = "Invalid Hotel id & User Id";
            return response()->json([
                'code' => VALIDATIONERROR,
                'message' => $customError
            ]);
        }
    }


    /**
     * Used for update all hotel review
     * 
     * @param  \Illuminate\Http\Request  $request 
     * @param  int $review_id 
     * 
     * @return Illuminate\Http\JsonResponse
     */
    public function updateHotelReviewData(Request $request, int $review_id): JsonResponse
    {
        // validate the hotel_id, user_id, review title & review description
        $validator = Validator::make($request->all(), [
            'hotel_id' => 'required|numeric',
            'user_id' => 'required|numeric',
            'review_title' => 'required|max:255',
            'review_data' => 'required|max:20000',
        ]);

        // found any error 
        if ($validator->fails()) {
            $customError = CommonHelper::customErrorResponse($validator->messages()->get('*'));
            return response()->json([
                'code' => VALIDATIONERROR,
                'message' => $customError
            ]);
        }

        $hoteDataExist = Hotel::find($request->hotel_id);
        $userDataExist = User::find($request->user_id);
        $reviewdata = Review::find($review_id);
        //found the valid user & hotel data
        if (!empty($hoteDataExist) && !empty($userDataExist) && !empty($reviewdata)) {

            $reviewdata = Review::find($request->review_id);
            $reviewdata->title = $request->review_title;
            $reviewdata->description = $request->review_data;
            $reviewdata->user_id = $request->user_id;
            $reviewdata->hotel_id = $request->hotel_id;
            $reviewdata->save();
            if ($reviewdata->id > 0) {
                return response()->json([
                    'code' => SUCCESS,
                    'message' => HOTELREVIEWUPDATEDMSG,

                ]);
            } else {
                // error
                $customError = "Review not updated, Please try again";
                return response()->json([
                    'code' => VALIDATIONERROR,
                    'message' => $customError
                ]);
            }
        } else {
            // error
            $customError = "Invalid Hotel id or User Id or Review Id";
            return response()->json([
                'code' => VALIDATIONERROR,
                'message' => $customError
            ]);
        }
    }

    /**
     * Used for delete the review of hotel
     * 
     * @param  \Illuminate\Http\Request  $request 
     * @param  int $review_id review_id
     * @return Illuminate\Http\JsonResponse
     */
    public function deleteHotelReview(Request $request, int $review_id): JsonResponse
    {
        $reviewDelete = Review::where('id', $review_id)->delete();

        if ($reviewDelete) {
            return response()->json([
                'code' => SUCCESS,
                'message' => HOTELREVIEWDELETEDMSG,

            ]);
        } else {
            // error
            $customError = "Review not found, Please try again";
            return response()->json([
                'code' => VALIDATIONERROR,
                'message' => $customError
            ]);
        }
    }
}
