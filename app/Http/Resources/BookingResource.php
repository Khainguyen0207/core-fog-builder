<?php

namespace App\Http\Resources;

use App\Enums\BaseEnum;
use Illuminate\Http\Request;

class BookingResource extends AbstractResource
{
    public function toArray(Request $request): array
    {
        return [
            'booking_code' => $this->booking_code,
            'transaction_code' => $this->transaction_code,
            'transaction' => TransactionResource::make($this->whenLoaded('transaction')),
            'status' => $this->transformEnum($this->status),
            'payment_method' => $this->transformEnum($this->payment_method),
            'price' => $this->price,
            'discount' => $this->discount,
            'total_price' => $this->total_price,
            'coupon_code' => $this->coupon_code,
            'customer_name' => $this->customer_name,
            'customer_phone' => $this->customer_phone,
            'customer_email' => $this->customer_email,
            'total_duration' => $this->total_duration,
            'scheduled_start' => $this->scheduled_start?->format('Y-m-d H:i:s'),
            'estimated_end' => $this->estimated_end?->format('Y-m-d H:i:s'),
            'actual_start' => $this->actual_start?->format('Y-m-d H:i:s'),
            'actual_end' => $this->actual_end?->format('Y-m-d H:i:s'),
            'bike_type' => $this->bike_type,
            'plate_number' => $this->plate_number,
            'note' => $this->note,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'services' => BookingServiceResource::collection(
                $this->whenLoaded('bookingServices')
            ),
            'customer' => new CustomerResource($this->whenLoaded('customer')),
        ];
    }
}
