<?php

namespace App\Http\Controllers;

use App\Http\Requests\OperationRequest;
use App\Account;
use App\Deposit;
use App\Transaction;

class DepositController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function doDeposit(Account $account,OperationRequest $request)
    {
        $transaction = Transaction::query()->find($request['transaction_id']);
        if($transaction->account_id != $account->id){
            return response()->json([
                'invalid transaction'
            ],422);
        }

        $deposit = new Deposit();
        $deposit->transaction_id = $request['transaction_id'];
        $deposit->currency_id = $request['currency_id'];
        $deposit->type = 'deposit';
        $deposit->amount = $request['amount'];
        $deposit->rate = $account->bank->currencies()->where('currencies.id','=',$deposit->currency_id)->first()->pivot->rate;
        $deposit->account_rate = $account->bank->currencies()->where('currencies.id','=',$account->currency_id)->first()->pivot->rate;

        return response()->json([
           'deposit' => $deposit->save(),
           'balance' => $account->refresh()->getBalance()
        ]);
    }
}
