<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SystemSettingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'work_schedule' => [
                'monday' => $this->resource['work_time_monday'] ?? null,
                'tuesday' => $this->resource['work_time_tuesday'] ?? null,
                'wednesday' => $this->resource['work_time_wednesday'] ?? null,
                'thursday' => $this->resource['work_time_thursday'] ?? null,
                'friday' => $this->resource['work_time_friday'] ?? null,
                'saturday' => $this->resource['work_time_saturday'] ?? null,
                'sunday' => $this->resource['work_time_sunday'] ?? null,
            ],
            'contact' => [
                'email' => $this->resource['system_email'] ?? null,
                'hotline' => $this->resource['system_hotline'] ?? null,
                'address' => $this->resource['system_address'] ?? null,
                'zalo_support' => $this->resource['system_zalo_support'] ?? null,
            ],
            'social' => [
                'facebook' => $this->resource['social_facebook'] ?? null,
                'instagram' => $this->resource['social_instagram'] ?? null,
                'threads' => $this->resource['social_thread'] ?? null,
                'tiktok' => $this->resource['social_tiktok'] ?? null,
            ],
        ];
    }
}
