<?php

namespace Database\Seeders;

use App\Actions\CreateBookingAction;
use App\Enums\BookingStatusEnum;
use App\Enums\PaymentMethodEnum;
use App\Models\Booking;
use App\Models\BookingService;
use App\Models\Customer;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingSeeder extends Seeder
{

    public function run(CreateBookingAction $action): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        BookingService::query()->truncate();
        Booking::query()->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $customers = Customer::query()->has('user')->get();

        if ($customers->isEmpty()) {
            return;
        }

        $servicesPool = Service::query()->where('status', 'enabled')->pluck('title', 'id')->toArray();
        $paymentMethods = [
            PaymentMethodEnum::BANK_TRANSFER,
            PaymentMethodEnum::PAY_LATER,
        ];

        $totalBookings = 1000;

        for ($i = 0; $i < $totalBookings; $i++) {
            try {
                /** @var Customer $customer */
                $customer = $customers->random();
                Auth::login($customer->user);

                $serviceIds = array_rand($servicesPool, 2);

                $services = [
                    [
                        'service_id' => $serviceIds[0],
                    ],
                    [
                        'service_id' => $serviceIds[1],
                    ]
                ];

                $date = Carbon::now()->subDays(rand(0, 30));

                if (rand(0, 1)) {
                    $date = Carbon::now()->addDays(rand(0, 14));
                }

                if ($date->dayOfWeek === 0) {
                    continue;
                }

                $hour = rand(8, 17);
                if ($date->dayOfWeek === 6 && $hour >= 12) {
                    $hour = rand(8, 11);
                }

                $scheduledStart = $date->setTime($hour, rand(0, 1) ? 0 : 30);
                $paymentMethod = $paymentMethods[array_rand($paymentMethods)];

                $bookingData = [
                    'customer_id' => $customer->id,
                    'customer_name' => $customer->name,
                    'customer_phone' => $customer->phone ?? fake()->phoneNumber,
                    'notify_email' => $customer->user->email,
                    'scheduled_start' => $scheduledStart,
                    'bike_type' => fake()->randomElement(['Wave', 'Vision', 'Air Blade', 'Winner', 'Exciter', 'SH Mode', 'Lead']),
                    'plate_number' => strtoupper(fake()->bothify('##?-#####')),
                    'services' => $services,
                    'note' => fake()->boolean(60) ? fake()->sentence : null,
                    'payment_method' => $paymentMethod,
                ];

                $processedBooking = $action->handle($bookingData);

                $newStatus = BookingStatusEnum::PENDING;
                if ($scheduledStart->isPast()) {
                    $newStatus = fake()->randomElement([BookingStatusEnum::DONE, BookingStatusEnum::CANCELLED]);
                } else {
                    $newStatus = fake()->randomElement([BookingStatusEnum::PENDING, BookingStatusEnum::CONFIRMED, BookingStatusEnum::IN_PROGRESS]);
                }

                $processedBooking->update([
                    'status' => $newStatus,
                ]);


                if ($newStatus === BookingStatusEnum::DONE) {
                    $processedBooking->update([
                        'actual_start' => $scheduledStart,
                        'actual_end' => $processedBooking->estimated_end,
                    ]);
                }
            } catch (\Exception $exception) {
                Log::error($exception->getMessage());
                continue;
            }
        }
    }
}
