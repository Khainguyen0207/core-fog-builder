<?php

namespace Database\Factories;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StaffFactory extends Factory
{
    protected $model = Staff::class;

    public function definition(): array
    {
        return [
            'staff_code' => $this->faker->unique()->regexify('STF[0-9]{5}'),
            'user_id' => User::factory(),
            'name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'level' => $this->faker->randomElement(['trainee', 'staff', 'technician', 'manager']),
            'is_active' => true,
            'salary' => $this->faker->randomFloat(2, 5000000, 20000000),
            'joined_at' => $this->faker->dateTimeBetween('-5 years', 'now'),
            'resigned_at' => null,
            'note' => $this->faker->sentence(),
        ];
    }
}
