<?php

namespace App;

use App\Http\Scopes\DepositScope;

class Deposit extends Operation
{
    protected $table = 'operations';

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new DepositScope());
    }
}
