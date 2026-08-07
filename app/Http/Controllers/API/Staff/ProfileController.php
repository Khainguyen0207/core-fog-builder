<?php

namespace App\Http\Controllers\API\Staff;

use App\Actions\ChangePasswordAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Staff\UpdateProfileRequest;
use App\Http\Resources\StaffResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        try {
            $staff = $request->user()->staff;

            if (!$staff) {
                return response()->json([
                    'error' => true,
                    'message' => 'Không tìm thấy thông tin nhân viên',
                ], 404);
            }

            $staff->update([
                'name' => $request->name,
                'note' => $request->note,
            ]);

            return response()->json([
                'error' => false,
                'data' => StaffResource::make($staff),
                'message' => 'Cập nhật thông tin thành công',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function changePassword(Request $request, ChangePasswordAction $action): JsonResponse
    {
        $request->validate([
            'current_password' => 'required|min:6',
            'password' => 'required|confirmed|min:6'
        ]);

        try {
            $action->handle($request->user(), $request->only('current_password', 'password'));

            return response()->json([
                'error' => false,
                'message' => 'Đổi mật khẩu thành công',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
