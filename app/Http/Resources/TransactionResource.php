<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'account_id' => $this->account_id,
            'account_number' => $this->account->account_number,
            'account_currency_id' => $this->account->currency_id,
            'account_currency' => $this->account->currency->name,
            'bank_id' => $this->account->bank_id,
            'bank' => $this->account->bank->name,
            'deposits' => OperationResource::collection($this->deposits),
            'withdraws' => OperationResource::collection($this->withdraws),
            'transfers' => TransferResource::collection($this->transfers),
            'date' => $this->created_at
        ];
    }
}
