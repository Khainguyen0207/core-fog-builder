<?php

namespace App\Http\Resources\Staff;

use App\Http\Resources\AbstractResource;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\StaffResource;
use App\Http\Resources\StaffReviewResource;
use Illuminate\Http\Request;

class StaffScheduleResource extends AbstractResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'booking' => $this->whenLoaded('booking', fn() => [
                'booking_code' => $this->booking->booking_code,
                'customer_name' => $this->booking->customer_name ?? $this->booking->customer->name ?? 'N/A',
                'bike_type' => $this->booking->bike_type,
                'plate_number' => $this->booking->plate_number,
                'note' => $this->booking->note,
            ]),
            'service' => ServiceResource::make($this->whenLoaded('service')),
            'price' => $this->price,
            'duration' => $this->duration,
            'status' => $this->transformEnum($this->status),
            'staff' => StaffResource::make($this->whenLoaded('staff')),
            'note' => $this->note,
            'review' => StaffReviewResource::make($this->whenLoaded('staffReview')),
            'started_at' => $this->started_at?->format('Y-m-d H:i:s'),
            'finished_at' => $this->finished_at?->format('Y-m-d H:i:s'),
        ];
    }
}
