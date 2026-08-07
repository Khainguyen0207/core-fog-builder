<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class StaffResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $skills = collect($this->services)->map(function ($skill) {
            return [
                'id' => $skill->id,
                'title' => $skill->title,
            ];
        });

        return [
            'id' => $this->id,
            'staff_code' => $this->staff_code,
            'name' => $this->name,
            'level' => $this->level->getLabel(),
            'skills' => $skills->toArray(),
            'avatar' => $this->avatar ? Storage::disk('public')->url($this->avatar) : null,
            'staffReviews' => StaffReviewResource::collection($this->whenLoaded('staffReviews')),
            'note' => $this->note,
            'rate' => $this->rate,
            'is_busy' => $this->when($this->resource->getAttribute('is_busy') !== null, $this->resource->getAttribute('is_busy')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
