<?php

namespace App\Http\Controllers\API;

use App\Actions\CreateStaffReviewAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\StaffReviewRequest;
use App\Http\Resources\StaffReviewResource;
use App\Models\BookingService;
use App\Models\StaffReview;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class StaffReviewController extends Controller
{
    public function store(StaffReviewRequest $request, CreateStaffReviewAction $action): JsonResponse
    {
        try {
            $customer = $request->user()->customer;

            if (!$customer) {
                return response()->json([
                    'error' => true,
                    'message' => 'Không tìm thấy thông tin khách hàng.',
                    'data' => null,
                ], 403);
            }

            $review = $action->handle($request->validated(), $customer->id);

            return response()->json([
                'error' => false,
                'message' => 'Đánh giá thành công.',
                'data' => StaffReviewResource::make($review),
            ], 201);
        } catch (Throwable $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'data' => null,
            ], 422);
        }
    }

    public function show(BookingService $bookingService): JsonResponse
    {
        $review = $bookingService->staffReview()
            ->with(['customer', 'staff'])
            ->first();

        if (!$review) {
            return response()->json([
                'error' => true,
                'message' => 'Không tìm thấy đánh giá cho dịch vụ này.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'error' => false,
            'message' => 'Lấy đánh giá thành công.',
            'data' => $review,
        ]);
    }


    public function index(Request $request): JsonResponse
    {
        $query = StaffReview::with(['customer', 'staff', 'bookingService']);

        if ($request->has('staff_id')) {
            $query->where('staff_id', $request->input('staff_id'));
        }

        if ($request->has('customer_id')) {
            $query->where('customer_id', $request->input('customer_id'));
        }

        $reviews = $query->latest()->paginate(10);

        return response()->json([
            'error' => false,
            'message' => 'Lấy danh sách đánh giá thành công.',
            'data' => $reviews,
        ]);
    }


    public function staffStats(int $staffId): JsonResponse
    {
        $stats = StaffReview::where('staff_id', $staffId)
            ->selectRaw('AVG(rating) as average_rating, COUNT(*) as review_count')
            ->first();

        return response()->json([
            'error' => false,
            'message' => 'Lấy thống kê đánh giá thành công.',
            'data' => [
                'staff_id' => $staffId,
                'average_rating' => round($stats->average_rating ?? 0, 1),
                'review_count' => $stats->review_count ?? 0,
            ],
        ]);
    }
}
