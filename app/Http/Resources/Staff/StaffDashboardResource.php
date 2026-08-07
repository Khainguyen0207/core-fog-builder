<?php

namespace App\Http\Resources\Staff;

use App\Http\Resources\BookingResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StaffDashboardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'today_count' => $this->resource['today_count'],
            'week_count' => $this->resource['week_count'],
            'month_count' => $this->resource['month_count'],
            'rate' => $this->resource['rate'],
            'upcoming_bookings' => BookingResource::collection($this->resource['upcoming_bookings']),
        ];
    }
}
