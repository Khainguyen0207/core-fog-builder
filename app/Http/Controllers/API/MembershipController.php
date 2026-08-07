<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MembershipSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Number;

class MembershipController extends Controller
{
    public function getMembership(Request $request): JsonResponse
    {
        $customer = $request->user()->customer;

        $totalSpent = (int) $customer->total_spent;

        $membership = MembershipSetting::query()
            ->where('min_points', '>', $totalSpent)
            ->orderBy('min_points', 'asc')
            ->first(['min_points', 'name', 'description']);

        $currentMembership = MembershipSetting::query()
            ->where('membership_code', $customer->membership_code->getValue())
            ->first(['min_points', 'name', 'description']);

        $data = [
            'level' => $currentMembership?->name,
            'total_spent' => $totalSpent,
            'target_level' => $membership?->name,
            'target_spent' => $membership?->min_points,
            'description' => $currentMembership?->description,
        ];

        return response()->json([
            'error' => false,
            'data' => $data,
            'message' => 'Lấy thông tin thành viên thành công.',
        ]);
    }
}
