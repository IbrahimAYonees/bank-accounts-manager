<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Currency;

class OperationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $currencies = Currency::all()->pluck('name','id');
        return [
            'id' => $this->id,
            'currency_id' => $this->currency_id,
            'currency' => $currencies[$this->currency_id],
            'type' => $this->type,
            'amount' => $this->amount,
            'rate' => $this->rate,
            'account_rate' => $this->account_rate,
            'balance_amount' => round(($this->amount * $this->rate) / $this->account_rate , 2),
            'date' => $this->created_at
        ];
    }
}
