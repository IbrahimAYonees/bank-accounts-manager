<?php

namespace App\Http\Controllers;

use App\Http\Requests\OperationRequest;
use App\Transaction;
use App\Account;
use App\Withdraw;

class WithdrawController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function doWithdraw(Account $account,OperationRequest $request)
    {
        $transaction = Transaction::query()->find($request['transaction_id']);
        if($transaction->account_id != $account->id){
            return response()->json([
                'invalid transaction'
            ],422);
        }

        $rate = $account->bank->currencies()->where('currencies.id','=',$request['currency_id'])->first()->pivot->rate;
        if($account->getBalanceInEGP() < $request['amount'] * $rate){
            $operations = $transaction->operations;
            foreach ($operations as $operation) {
                $operation->delete();
            }
            $transaction->delete();
            return response()->json([
                'insufficient balance'
            ],422);
        }

        $withdraw = new Withdraw();
        $withdraw->transaction_id = $request['transaction_id'];
        $withdraw->currency_id = $request['currency_id'];
        $withdraw->type = 'withdraw';
        $withdraw->amount = $request['amount'];
        $withdraw->rate = $rate;
        $withdraw->account_rate = $account->bank->currencies()->where('currencies.id','=',$account->currency_id)->first()->pivot->rate;

        return response()->json([
            'withdraw' => $withdraw->save(),
            'balance' => $account->refresh()->getBalance()
        ]);
    }
}
