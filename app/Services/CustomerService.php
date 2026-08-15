<?php

namespace App\Services;

use App\Enums\CustomerMemberShipEnum;
use App\Enums\UserGroupRoleEnum;
use App\Events\CustomerCreatedEvent;
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

            CustomerCreatedEvent::dispatch($customer);

            return $customer->fresh();
        });
    }
}
