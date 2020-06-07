<?php


namespace App\Http\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class AccountsTransactionsScope implements Scope
{

    public function apply(Builder $builder, Model $model)
    {
        return $builder->select('transactions.*')
            ->leftJoin('accounts','transactions.account_id','=','accounts.id');
    }
}
