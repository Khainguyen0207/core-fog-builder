<?php

namespace App\Actions;

use App\Models\Customer;
use App\Services\CustomerService;

class CreateCustomerAction
{
    public function __construct(
        protected CustomerService $customerService
    ) {}

    public function handle(array $data): Customer
    {
        return $this->customerService->create($data);
    }
}
