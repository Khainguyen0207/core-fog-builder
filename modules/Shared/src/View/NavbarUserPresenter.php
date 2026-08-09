<?php

namespace Modules\Shared\View;

use BackedEnum;
use Stringable;
use Throwable;

class NavbarUserPresenter
{
    public function present(mixed $user): array
    {
        $email = $this->stringValue(data_get($user, 'email'));
        $avatar = $this->stringValue(data_get($user, 'avatar'));
        $role = data_get($user, 'group_role', data_get($user, 'role'));

        return [
            'email' => $email,
            'avatar' => $avatar !== '' ? $avatar : (string) config('figure-admin-shared.user.default_avatar', 'default-avatar.png'),
            'role' => $this->roleLabel($role),
        ];
    }

    private function roleLabel(mixed $role): string
    {
        if (is_object($role) && method_exists($role, 'getLabel')) {
            try {
                return $this->stringValue($role->getLabel());
            } catch (Throwable) {
                return '';
            }
        }

        if ($role instanceof BackedEnum) {
            return $this->stringValue($role->value);
        }

        return $this->stringValue($role);
    }

    private function stringValue(mixed $value): string
    {
        return is_scalar($value) || $value instanceof Stringable ? (string) $value : '';
    }
}
