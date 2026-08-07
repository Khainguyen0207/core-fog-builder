<?php

namespace App\Http\Middleware;

use App\Enums\UserGroupRoleEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StaffAreaMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'error' => true,
                'data' => null,
                'message' => 'Tài khoản không tồn tại',
            ], 401);
        }

        if ($user->group_role->getValue() !== UserGroupRoleEnum::STAFF) {
            return response()->json([
                'error' => true,
                'data' => null,
                'message' => 'Tài khoản không có quyền truy cập',
            ], 403);
        }

        return $next($request);
    }
}
