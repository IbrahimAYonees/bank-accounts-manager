<?php

namespace App\Http\Resources;

use App\Currency;
use Illuminate\Http\Resources\Json\JsonResource;

class TransferResource extends JsonResource
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
            'amount' => $this->amount,
            'rate' => $this->rate,
            'account_rate' => $this->account_rate,
            'balance_amount' => round(($this->amount * $this->rate) / $this->account_rate , 2),
            'to_bank' => $this->to_bank_name,
            'to_account' => $this->to_bank_account_number,
            'date' => $this->created_at,
            'canceled' => $this->canceled
        ];
    }
}
