<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Http\Scopes\UserScope;

class Account extends Model
{
    /**
     * @var string[]
     */
    protected $casts = [
        'active' => 'boolean'
    ];

    public static function boot()
    {
        parent::boot();

        //check if the request is from admin pages or the app is working from console
        if(method_exists(app(), 'runningInConsole') && app()->runningInConsole()){
            return;
        }

        static::addGlobalScope(new UserScope());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeWithRelations(Builder $builder)
    {
        return $builder->with(['bank','currency','bank.currencies']);
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeWithTransactions(Builder $builder)
    {
        return $builder->with('transactions');
    }

    /**
     * @param Builder $builder
     * @param string $sortable
     * @param string $sortType
     * @return Builder
     */
    public function scopeSort(Builder $builder, $sortable = 'created_at', $sortType = 'desc')
    {
        if(!$this->isSortable($sortable)){
            $sortable = 'created_at';
            $sortType = 'desc';
        }
        return $builder->orderBy($sortable,$sortType);
    }

    public function getBalanceInEGP()
    {
        $transactions = $this->transactions()->with(['deposits','withdraws','transfers'])->get();
        $balanceInEGP = 0;
        foreach ($transactions as $transaction) {
            $deposits = $transaction->deposits()->get();
            $withdraws = $transaction->withdraws()->get();
            $transfers = $transaction->transfers()->get();
            foreach ($deposits as $deposit) {
                $balanceInEGP += $deposit->amount * $deposit->rate;
            }
            foreach ($withdraws as $withdraw) {
                $balanceInEGP -= $withdraw->amount * $withdraw->rate;
            }
            foreach ($transfers as $transfer) {
                if($transfer->caneled){
                    continue;
                }
                $balanceInEGP -= $transfer->amount * $transfer->rate;
            }
        }
        return round($balanceInEGP,2);
    }

    public function getBalance()
    {
        $accountBankCurrencies = $this->bank->currencies()->get()->pluck('pivot.rate','id')->toArray();
        $balance = $this->getBalanceInEGP() / $accountBankCurrencies[$this->currency_id];
        return round($balance,2);
    }

    /**
     * @param string $sortable
     * @return bool
     */
    private function isSortable(string $sortable = null)
    {
        return !empty($this->sortsMap()[$sortable]);
    }

    /**
     * @return string[]
     */
    private function sortsMap()
    {
        return [
            'bank' => 'bank_id',
            'currency' => 'currency_id',
            'type' => 'account_type',
            'branch' => 'branch',
            'active' => 'active',
            'date' => 'created_at'
        ];
    }


}
