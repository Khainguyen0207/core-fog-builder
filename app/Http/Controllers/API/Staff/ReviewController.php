<?php

namespace App\Http\Controllers\API\Staff;

use App\Http\Controllers\Controller;
use App\Http\Resources\Staff\StaffReviewItemResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $staff = $request->user()->staff;

        $reviews = $staff->staffReviews()
            ->with(['customer', 'bookingService'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return response()->json([
            'error' => false,
            'data' => StaffReviewItemResource::collection($reviews)->response()->getData(true),
            'message' => 'Lấy đánh giá thành công',
        ]);
    }
}
