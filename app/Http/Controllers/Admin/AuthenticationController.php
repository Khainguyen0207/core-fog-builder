<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserGroupRoleEnum;
use App\Http\Requests\Admin\LoginRequest;
use Illuminate\Support\Facades\Auth;

class AuthenticationController
{
    public function login()
    {
        return view('admin.pages.auth.login');
    }

    public function authenticate(LoginRequest $request)
    {
        $data = $request->validated();
        $remember = $request->boolean('is_remember_me');

        $isLogin = Auth::attempt([
            'email' => $data['email'],
            'password' => $data['password'],
            'group_role' => UserGroupRoleEnum::ADMIN,
            'is_active' => 1,
        ], $remember);

        if ($isLogin) {
            request()->user()->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->getClientIp(),
            ]);

            return redirect()->route('admin.dashboard.index');
        }

        return redirect()->back()->withErrors(['error' => 'Tài khoản hoặc mật khẩu không chính xác hoặc bị khóa']);
    }

    public function logout()
    {
        auth()->logout();

        return redirect()->route('login');
    }
}
