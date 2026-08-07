<?php

namespace App\Http\Controllers\API;

use App\Actions\CreateCustomerAction;
use App\Enums\UserGroupRoleEnum;
use App\Http\Resources\CustomerResource;
use App\Models\User;
use App\Services\GoogleService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class GoogleAuthController
{
    public function login(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string'],
        ], [
            'code.required' => 'Mã xác thực là bắt buộc.',
            'code.string' => 'Mã xác thực phải là chuỗi ký tự.',
        ]);

        $code = $request->input('code');

        if (empty($code)) {
            return response()->json([
                'error' => true,
                'data' => null,
                'message' => 'Vui lòng cung cấp mã xác thực',
            ]);
        }

        $payload = GoogleService::verifyAccessToken($code);

        if (! $payload) {
            return response()->json([
                'error' => true,
                'data' => null,
                'message' => 'Mã xác thực không hợp lệ',
            ]);
        }

        $sub = $payload['sub'] ?? null;

        if (! $sub) {
            return response()->json([
                'error' => true,
                'data' => null,
                'message' => 'Mã xác thực không hợp lệ',
            ]);
        }

        $user = User::query()->where('email', $payload['email'])->first();

        $statusCode = 200;
        $message = 'Tìm thấy người dùng';

        if ($user) {
            $user->update([
                'last_login_at' => Carbon::now(),
                'last_login_ip' => $request->ip(),
                'google_sub' => $sub,
            ]);
        } else {
            $statusCode = 201;
            $message = 'Đã tạo người dùng mới';

            $user = User::create([
                'email' => $payload['email'],
                'password' => Hash::make($sub),
                'group_role' => UserGroupRoleEnum::CUSTOMER,
                'last_login_at' => Carbon::now(),
                'last_login_ip' => $request->ip(),
                'google_sub' => $sub,
            ]);
        }

        $customer = $user->customer;

        if ($customer) {
            return response()->json([
                'error' => false,
                'data' => [
                    'token' => $user->createToken('auth')->plainTextToken,
                    'customer' => CustomerResource::make($customer),
                    'is_new_user' => false,
                ],
                'message' => 'Chào mừng ' . $customer->name . '! Đăng nhập bằng Google thành công.',
            ], $statusCode);
        }

        return response()->json([
            'error' => false,
            'data' => [
                'email' => $user->email,
                'name' => $payload['name'] ?? null,
                'avatar' => $payload['picture'] ?? null,
                'is_new_user' => true,
            ],
            'message' => $message,
        ], $statusCode);
    }

    public function completeProfile(Request $request)
    {
        $request->validate([
            'email' => 'required|exists:users,email',
            'phone' => ['required', 'unique:customers,phone', 'regex:/((09|03|07|08|05)+([0-9]{8})\b)/'],
            'name' => 'required|string|min:6',
        ], [
            'email.required' => 'Email là bắt buộc.',
            'email.exists' => 'Email không tồn tại trong hệ thống.',
            'phone.required' => 'Số điện thoại là bắt buộc.',
            'phone.unique' => 'Số điện thoại đã được sử dụng.',
            'phone.regex' => 'Số điện thoại không hợp lệ.',
            'name.required' => 'Tên là bắt buộc.',
            'name.string' => 'Tên phải là chuỗi ký tự.',
            'name.min' => 'Tên phải có ít nhất 6 ký tự.',
        ]);

        $user = User::query()->where('email', $request->email)->first();

        if (! $user->customer) {
            $customer = app(CreateCustomerAction::class)->handle($request->all());
        } else {
            $customer = $user->customer;
        }

        $token = $user->createToken('auth')->plainTextToken;

        return response()->json([
            'error' => false,
            'data' => [
                'token' => $token,
                'customer' => CustomerResource::make($customer),
            ],
            'message' => 'Chào mừng ' . $customer->name . '! Đăng nhập bằng Google thành công.',
        ]);
    }

    public function checkCustomer(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'email' => 'required|email',
        ], [
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không đúng định dạng.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'data' => null,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        User::query()->where('email', $request->query('email'))->firstOrFail();

        return response()->json([
            'error' => false,
            'data' => null,
            'message' => 'Tìm thấy khách hàng',
        ]);
    }
}
