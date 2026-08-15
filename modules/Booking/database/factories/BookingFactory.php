<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        $customer = Customer::factory()->create();

        return [
            'booking_code' => $this->faker->unique()->regexify('BK[0-9]{6}'),
            'customer_id' => $customer->id,
            'customer_name' => $customer->name,
            'customer_phone' => $customer->phone,
            'notify_email' => $this->faker->safeEmail(),
            'scheduled_start' => $this->faker->dateTimeBetween('now', '+1 month'),
            'status' => 'pending',
            'note' => $this->faker->sentence(),
        ];
    }
}
