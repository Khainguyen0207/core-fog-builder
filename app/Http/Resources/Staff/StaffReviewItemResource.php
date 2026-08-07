<?php

namespace App\Http\Resources\Staff;

use App\Http\Resources\CustomerResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StaffReviewItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'rating' => $this->rating,
            'note' => $this->note,
            'customer' => CustomerResource::make($this->whenLoaded('customer')),
            'service_name' => $this->whenLoaded('bookingService', fn() => $this->bookingService->service_name),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
