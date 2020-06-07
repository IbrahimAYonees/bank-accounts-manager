<?php

namespace App;

use App\Http\Scopes\WithdrawScope;

class Withdraw extends Operation
{
    protected $table = 'operations';

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new WithdrawScope());
    }
}
