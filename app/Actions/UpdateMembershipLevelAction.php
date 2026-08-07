<?php

namespace App\Actions;

use App\Models\Customer;
use App\Models\MembershipSetting;
use Illuminate\Support\Number;

class UpdateMembershipLevelAction
{
    public function handle(Customer $customer): Customer
    {
        $totalSpent = Number::parse($customer->total_spent) ?? 0;

        $membershipSetting = MembershipSetting::query()
            ->where('min_points', '<=', $totalSpent)
            ->orderByDesc('min_points')
            ->first();

        if ($membershipSetting && $customer->membership_code !== $membershipSetting->membership_code) {
            $customer->update([
                'membership_code' => $membershipSetting->membership_code,
            ]);
        }

        return $customer->fresh();
    }
}
