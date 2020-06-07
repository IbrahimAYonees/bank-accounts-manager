<?php

namespace App\Http\Controllers;

use App\Account;
use App\Bank;

class StatisticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getAccountStatistics()
    {
        $userId = auth()->user()->id;
        $banks = Bank::all()->pluck('name','id')->toArray();
        $banksBalances = [];
        $accounts = Account::query()->where('user_id','=',$userId)->get();

        foreach ($accounts as $account) {
            if(!isset($banksBalances[$banks[$account->bank_id]])){
                $banksBalances[$banks[$account->bank_id]] = 0;
            }
            $banksBalances[$banks[$account->bank_id]] += $account->getBalanceInEGP();
        }


        return response()->json([
            'banksBalances' => $banksBalances
        ]);
    }
}
