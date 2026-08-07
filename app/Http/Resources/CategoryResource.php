<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'create_at' => Carbon::parse($this->resource->created_at)->toDateTimeString(),
            'update_at' => Carbon::parse($this->resource->update_at)->toDateTimeString(),
        ];
    }
}
