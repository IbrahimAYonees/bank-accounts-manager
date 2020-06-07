<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function banksRates()
    {
        return $this->belongsToMany(Bank::class)->withPivot('rate');
    }
}
