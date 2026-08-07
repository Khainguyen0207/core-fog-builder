<?php

namespace App\Http\Resources;

use App\Enums\BaseEnum;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class AbstractResource extends JsonResource
{
    protected function transformEnum(mixed $data): mixed
    {
        if ($data instanceof BaseEnum) {
            return [
                'value' => $data->getValue(),
                'label' => $data->getLabel(),
                'color' => $data->getColor(),
            ];
        }

        return $data;
    }
}
