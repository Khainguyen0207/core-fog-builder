<?php

namespace App\Events;

use App\Models\Customer;
use Illuminate\Foundation\Events\Dispatchable;

class CustomerCreatedEvent
{
    use Dispatchable;

    public function __construct(public Customer $customer) {}
}
