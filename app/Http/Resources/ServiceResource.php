<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'subtitle' => $this->resource->subtitle,
            'description' => $this->resource->description,
            'category' => CategoryResource::make($this->resource->category),
            'status' => $this->resource->status,
            'price' => $this->resource->price,
            'time_do' => $this->resource->time_do,
            'time_unit' => $this->resource->time_unit,
            'priority' => $this->resource->priority,
            'created_at' => Carbon::parse($this->resource->created_at)->toDateTimeString(),
            'updated_at' => Carbon::parse($this->resource->updated_at)->toDateTimeString(),
        ];
    }
}
