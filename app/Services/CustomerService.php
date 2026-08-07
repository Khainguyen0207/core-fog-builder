<?php

namespace App\Services;

use App\Enums\BookingStatusEnum;
use App\Enums\CustomerMemberShipEnum;
use App\Enums\UserGroupRoleEnum;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CustomerService
{
    public function create(array $data): Customer
    {
        return DB::transaction(function () use ($data) {
            $user = User::query()->where('email', $data['email'])->first();

            if (! $user) {
                $user = User::query()->create([
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                    'group_role' => UserGroupRoleEnum::CUSTOMER,
                ]);
            }

            $customer = Customer::create([
                'user_id' => $user->id,
                'name' => $data['name'],
                'phone' => $data['phone'],
                'membership_code' => $data['membership_code'] ?? CustomerMemberShipEnum::DEFAULT,
                'note' => $data['note'] ?? null,
            ]);

            Booking::query()
                ->where('customer_phone', $customer->phone)
                ->whereNull('customer_id')
                ->update(['customer_id' => $customer->id]);

            $totalSpent = Booking::query()
                ->where('customer_phone', $customer->phone)
                ->where('status', BookingStatusEnum::DONE)
                ->sum('total_price');

            $customer->update(['total_spent' => $totalSpent]);

            return $customer->fresh();
        });
    }
}
