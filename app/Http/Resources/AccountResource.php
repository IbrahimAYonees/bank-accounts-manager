<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
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
            'bank_id' => $this->bank_id,
            'bank' => $this->bank->name,
            'currency_id' => $this->currency_id,
            'currency' => $this->currency->name,
            'user_id' => $this->user_id,
            'branch' => $this->branch,
            'type' => $this->account_type,
            'number' => $this->account_number,
            'active' => $this->active,
            'date' => $this->created_at
        ];
    }
}
