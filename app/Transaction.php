<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Http\Scopes\AccountsTransactionsScope;
use App\Http\Scopes\UserScope;
use Carbon\Carbon;

class Transaction extends Model
{
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new AccountsTransactionsScope());

        if(method_exists(app(), 'runningInConsole') && app()->runningInConsole()){
            return;
        }

        static::addGlobalScope(new UserScope());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function operations()
    {
        return $this->hasMany(Operation::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function withdraws()
    {
        return $this->hasMany(Withdraw::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transfers()
    {
        return $this->hasMany(Transfer::class);
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeWithAccount(Builder $builder)
    {
        return $builder->with(['account','account.bank','account.currency','account.bank.currencies']);
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeWithOperations(Builder $builder)
    {
        return $builder->with(['operations']);
    }

    /**
     * @param Builder $builder
     * @param null $filter
     * @return Builder
     */
    public function scopeOperationsFilter(Builder $builder,$filter = null)
    {
        return !$filter ?
            $builder->with(['deposits','withdraws','transfers']) :
            $builder->with($filter)->has($filter);
    }

    /**
     * @param Builder $builder
     * @param null $accountId
     * @return Builder
     */
    public function scopeAccountFilter(Builder $builder,$accountId = null)
    {
        if(!$accountId){
            return $builder;
        }
        return $builder->where('accounts.id','=',$accountId);
    }

    /**
     * @param Builder $builder
     * @param null $bankId
     * @return Builder
     */
    public function scopeBankFilter(Builder $builder,$bankId = null)
    {
        if(!$bankId){
            return $builder;
        }
        return $builder->where('accounts.bank_id','=',$bankId);
    }

    /**
     * @param Builder $builder
     * @param null $accountNumber
     * @return Builder
     */
    public function scopeAccountNumberFilter(Builder $builder,$accountNumber = null)
    {
        if(!$accountNumber){
            return $builder;
        }
        return $builder->where('accounts.account_number','=',$accountNumber);
    }

    /**
     * @param Builder $builder
     * @param null $from
     * @param null $to
     * @return Builder
     * @throws \Exception
     */
    public function scopeDateFilter(Builder $builder,$from = null,$to = null)
    {
        if(!$from || !$to){
            return $builder;
        }
        $from = new Carbon($from);
        $to = new Carbon($to);
        return $builder->whereBetween('transactions.created_at',[$from,$to]);
    }
}
