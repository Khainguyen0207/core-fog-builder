<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'token' => $this->resource->token,
            'transaction_code' => $this->resource->transaction_code,
            'amount' => $this->resource->amount,
            'customer_name' => $this->resource->account_name,
            'customer_phone' => $this->resource->customer_phone,
            'bank_name' => $this->resource->bank_name,
            'status' => $this->resource->status,
            'account_number' => $this->resource->account_number,
        ];
    }
}
