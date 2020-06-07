<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function currencies()
    {
        return $this->belongsToMany(Currency::class)->withPivot('rate');
    }
}
