<?php

namespace App\Http\Controllers\API;

use App\Actions\CreateCustomerAction;
use App\Enums\BaseStatusEnum;
use App\Enums\UserGroupRoleEnum;
use App\Http\Requests\API\LoginRequest;
use App\Http\Requests\API\RegisterRequest;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\StaffResource;
use App\Jobs\MailJob;
use App\Models\Customer;
use App\Models\OneTimePassword;
use App\Models\PasswordResetToken;
use App\Models\Staff;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthenticateController
{
    public function me()
    {
        try {
            $user = request()->user();

            if (! $user || ! $user->is_active) {
                throw new Exception("Tài khoản không tồn tại or bị khóa", 401);
            }

            if ($user->group_role->getValue() === UserGroupRoleEnum::CUSTOMER) {
                return $this->customerSanctum($user->customer);
            }

            if ($user->group_role->getValue() === UserGroupRoleEnum::STAFF) {
                return $this->staffSanctum($user->staff);
            }

            return response()->json([
                'error' => true,
                'data' => null,
                'message' => 'Tài khoản không có quyền truy cập',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'data' => null,
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }

    public function staffSanctum(Staff $staff)
    {
        if (! $staff) {
            throw new Exception("Nhân viên chưa được đăng ký", 401);
        }

        return response()->json([
            'error' => false,
            'data' => StaffResource::make($staff),
            'message' => 'Lấy thông tin cá nhân thành công',
        ]);
    }

    public function customerSanctum(Customer $customer)
    {
        if (! $customer) {
            throw new Exception("Khách hàng chưa được đăng ký", 401);
        }

        return response()->json([
            'error' => false,
            'data' => CustomerResource::make($customer),
            'message' => 'Lấy thông tin cá nhân thành công',
        ]);
    }

    public function register(RegisterRequest $request, CreateCustomerAction $action)
    {
        try {
            $customer = $action->handle($request->validated());

            return response()->json([
                'error' => false,
                'data' => CustomerResource::make($customer->load('user')),
                'message' => 'Đăng ký thành công',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'data' => null,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $request = $request->validated();

            if (Auth::guard()->attempt([
                'email' => $request['email'],
                'password' => $request['password'],
                'is_active' => true,
                'group_role' => UserGroupRoleEnum::CUSTOMER,
            ])) {
                $user = Auth::user();

                $user->update([
                    'last_login_at' => Carbon::now(),
                    'last_login_ip' => request()->ip(),
                ]);

                $token = $user->createToken('auth')->plainTextToken;

                return response()->json([
                    'error' => false,
                    'data' => [
                        'token' => $token,
                    ],
                    'message' => 'Chào mừng quay lại ' . $user->customer->name . '!',
                ]);
            } else {
                $release = 'Tài khoản hoặc mật khẩu không chính xác';
            }
        } catch (\Exception $e) {
            $release = $e->getMessage();
        }

        return response()->json([
            'error' => true,
            'data' => null,
            'message' => $release,
        ]);
    }

    public function staffLogin(LoginRequest $request)
    {
        try {
            $request = $request->validated();
            if (Auth::guard()->attempt([
                'email' => $request['email'],
                'password' => $request['password'],
                'is_active' => true,
                'group_role' => UserGroupRoleEnum::STAFF,
            ])) {
                $user = Auth::user();

                if (! $user->staff || ! $user->staff->is_active) {
                    return response()->json([
                        'error' => true,
                        'data' => null,
                        'message' => 'Tài khoản hoặc mật khẩu không chính xác',
                    ]);
                }

                $user->update([
                    'last_login_at' => Carbon::now(),
                    'last_login_ip' => request()->ip(),
                ]);

                $token = $user->createToken('auth')->plainTextToken;

                return response()->json([
                    'error' => false,
                    'data' => [
                        'token' => $token,
                    ],
                    'message' => 'Chào mừng quay lại ' . $user->staff->name . '!',
                ]);
            } else {
                $release = 'Tài khoản hoặc mật khẩu không chính xác';
            }
        } catch (\Exception $e) {
            $release = $e->getMessage();
        }

        return response()->json([
            'error' => true,
            'data' => null,
            'message' => $release,
        ]);
    }

    public function forgetPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|exists:users,email',
            ]);

            $otp = OneTimePassword::query()
                ->where('email', $request['email'])
                ->where('expired_at', '>', Carbon::now())
                ->first();

            $user = User::query()
                ->with(['customer', 'staff'])
                ->where('email', $request['email'])
                ->first();

            if ($otp) {
                $expiredAt = Carbon::parse($otp->expired_at);
                $now = Carbon::now();
                $secondsLeft = $now->diffInSeconds($expiredAt);

                if ($secondsLeft > 480) {
                    return response()->json([
                        'error' => true,
                        'data' => null,
                        'message' => 'Bạn đã yêu cầu OTP quá nhiều lần. Vui lòng thử lại sau 2 phút.',
                    ]);
                } else {
                    $otp->update([
                        'expired_at' => Carbon::now()->addMinutes(10),
                    ]);
                }
            } else {
                $code = rand(100000, 999999);

                $otp = OneTimePassword::query()->create([
                    'user_id' => $user->id,
                    'email' => $request['email'],
                    'token' => Str::random(60),
                    'code' => $code,
                    'expired_at' => Carbon::now()->addMinutes(10),
                ]);
            }

            $from = 'tkhai12386@gmail.com';
            $to = $request['email'];
            $subject = 'Verify Your Email with OTP - ' . $otp->code;
            $title = config('app.name') . ' - Email Verification';
            $message = $otp->code;
            $template = config('mail.templates.otp-template');

            MailJob::dispatch($from, $to, $subject, $message, $title, $template, true);

            return response()->json([
                'error' => false,
                'data' => [
                    'email' => $request['email'],
                    'token' => $otp->token,
                    'expired_at' => $otp->expired_at,
                ],
                'message' => 'Vui lòng kiểm tra email và nhập mã OTP để hoàn tất xác thực.',
            ]);
        } catch (\Exception $e) {
            $release = 'Không tìm thấy tài khoản với email này.';

            return response()->json([
                'error' => true,
                'data' => null,
                'message' => $release,
            ]);
        }
    }

    public function verifyOtp(Request $request)
    {
        $token = $request->query('token');
        $email = $request->query('email');

        if (! $token || ! $email) {
            return response()->json([
                'error' => true,
                'data' => null,
                'message' => 'Mã OTP không hợp lệ.',
            ])->setStatusCode(404);
        }

        $otp = OneTimePassword::query()
            ->where('token', $token)
            ->where('email', $email)
            ->where('expired_at', '>', Carbon::now())->first();

        if (! $otp) {
            return response()->json([
                'error' => true,
                'data' => null,
                'message' => 'Mã OTP không hợp lệ.',
            ])->setStatusCode(404);
        }

        return response()->json([
            'error' => false,
            'data' => [
                'email' => $email,
                'token' => $token,
                'expired_at' => $otp->expired_at,
            ],
            'message' => 'Mã OTP hợp lệ.',
        ]);
    }

    public function verifyOtpPost(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|exists:users,email',
                'token' => 'required|string|exists:one_time_password,token',
                'code' => 'required|string',
            ]);

            $email = $request->input('email');
            $token = $request->input('token');

            $otp = OneTimePassword::query()
                ->where('token', $token)
                ->where('email', $email)
                ->where('expired_at', '>', Carbon::now())
                ->first();

            if (! $otp) {
                return response()->json([
                    'error' => true,
                    'data' => null,
                    'message' => 'Mã OTP không hợp lệ hoặc đã hết hạn.',
                ])->setStatusCode(404);
            }

            if ($otp->code !== $request->input('code')) {
                return response()->json([
                    'error' => true,
                    'data' => null,
                    'message' => 'Mã xác thực không đúng. Vui lòng thử lại.',
                ]);
            }

            $token = Str::random(255);

            PasswordResetToken::query()->updateOrCreate([
                'email' => $email,
                'token' => $token,
                'created_at' => Carbon::now(),
            ]);

            $otp->delete();

            return response()->json([
                'error' => false,
                'data' => [
                    'email' => $email,
                    'token' => $token,

                ],
                'message' => 'Xác thực OTP thành công. Vui lòng đặt lại mật khẩu của bạn.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'data' => null,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string',
                'token' => 'required|string',
                'password' => 'required|string|confirmed|min:6',
            ]);

            $exist = PasswordResetToken::query()
                ->where('email', $request['email'])
                ->where('token', $request['token'])
                ->first();

            if (! $exist) {
                return response()->json([
                    'error' => true,
                    'data' => null,
                    'message' => 'Mã OTP không hợp lệ hoặc đã hết hạn.',
                ]);
            }

            User::query()->where('email', $request['email'])->update([
                'password' => bcrypt($request->input('password')),
            ]);

            PasswordResetToken::query()
                ->where('email', $request['email'])
                ->where('token', $request['token'])->delete();

            return response()->json([
                'error' => false,
                'data' => null,
                'message' => 'Đặt lại mật khẩu thành công.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'data' => null,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function logout(Request $request)
    {
        $res = [
            'error' => false,
            'data' => null,
            'message' => 'Thành công',
        ];

        try {
            $request->user()->currentAccessToken()->delete();

            $res['message'] = 'Đăng xuất thành công';
        } catch (\Exception $e) {
            $res['message'] = $e->getMessage();
            $res['error'] = true;
        }

        return response()->json($res);
    }
}
