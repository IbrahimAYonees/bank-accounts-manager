<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferRequest;
use App\Account;
use App\Transaction;
use App\Transfer;

class TransferController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function doTransfer(Account $account,TransferRequest $request)
    {
        $transaction = Transaction::query()->find($request['transaction_id']);
        if($transaction->account_id != $account->id){
            return response()->json([
                'invalid transaction'
            ],422);
        }

        $rate = $account->bank->currencies()->where('currencies.id','=',$request['currency_id'])->first()->pivot->rate;
        if($account->getBalanceInEGP() < $request['amount'] * $rate){
            $transaction->delete();
            return response()->json([
                'insufficient balance'
            ],422);
        }

        $transfer = new Transfer();
        $transfer->transaction_id = $request['transaction_id'];
        $transfer->currency_id = $request['currency_id'];
        $transfer->amount = $request['amount'];
        $transfer->rate = $rate;
        $transfer->account_rate = $account->bank->currencies()->where('currencies.id','=',$account->currency_id)->first()->pivot->rate;
        $transfer->to_bank_name = $request['to_bank'];
        $transfer->to_bank_account_number = $request['to_bank_account_number'];

        return response()->json([
            'transfer' => $transfer->save(),
            'balance' => $account->refresh()->getBalance()
        ]);
    }

    public function cancelTransfer(Transfer $transfer)
    {
        return response()->json([
            'canceled' => $transfer->cancelTransfer()
        ]);
    }
}
