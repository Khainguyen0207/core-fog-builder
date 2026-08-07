<?php

namespace App\Actions;

use Illuminate\Support\Facades\Hash;
use Exception;

class ChangePasswordAction
{
    /**
     * @throws Exception
     */
    public function handle($user, array $data): void
    {
        if (!Hash::check($data['current_password'], $user->password)) {
            throw new Exception('Mật khẩu hiện tại không chính xác', 400);
        }

        $user->update([
            'password' => Hash::make($data['password']),
        ]);
    }
}
