<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingServiceResource extends AbstractResource
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
            'booking_code' => $this->whenLoaded('booking', fn() => $this->booking->booking_code),
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
