<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Account;

class AccountBalanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getBalance(Account $account)
    {
        return response()->json([
            'balance' => $account->getBalance()
        ]);
    }
}
