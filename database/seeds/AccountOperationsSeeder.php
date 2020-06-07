<?php

use Illuminate\Database\Seeder;
use App\Transaction;
use App\Deposit;
use App\Withdraw;
use App\Account;
use App\Transfer;
use Faker\Factory;

class AccountOperationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $accounts = Account::all();
        $banks = [
            'QNB','HSBC','CIB'
        ];
        $faker = Factory::create();
        foreach ($accounts as $account) {
            $accountBankCurrencies = $account->bank->currencies()->get();
            $rates = $accountBankCurrencies->pluck('pivot.rate','id');
            $transactions = Transaction::withAccount()->accountFilter($account->id)->get();
            $transferTransactions = [];

            foreach ($transactions as $transaction) {
                if(rand(0,1)){
                    $transferTransactions[] = $transaction;
                    continue;
                }
                for($i=0; $i<rand(1,3); $i++){
                    $deposit = rand(0,1);
                    if($deposit){
                        $amountWithEGP = rand(1000,10000);
                        $currency = $accountBankCurrencies->random();
                        $amount = round($amountWithEGP / $currency->pivot->rate , 2);
                        $depositOperation = new Deposit();
                        $depositOperation->transaction_id = $transaction->id;
                        $depositOperation->currency_id = $currency->id;
                        $depositOperation->amount = $amount;
                        $depositOperation->rate = $currency->pivot->rate;
                        $depositOperation->account_rate = $rates[$account->currency_id];
                        $depositOperation->type = 'deposit';
                        $depositOperation->save();
                    }else {
                        $account->refresh();
                        $balance = $account->getBalance();
                        $balanceWithEGP = round($balance * $rates[$account->currency_id] , 2);
                        if($balanceWithEGP <= 1500){
                            continue;
                        }
                        $amountWithEGP = rand(100,1000);
                        $currency = $accountBankCurrencies->random();
                        $amount = round($amountWithEGP / $currency->pivot->rate , 2);
                        $withdrawOperation = new Withdraw();
                        $withdrawOperation->transaction_id = $transaction->id;
                        $withdrawOperation->currency_id = $currency->id;
                        $withdrawOperation->amount = $amount;
                        $withdrawOperation->type = 'withdraw';
                        $withdrawOperation->rate = $currency->pivot->rate;
                        $withdrawOperation->account_rate = $rates[$account->currency_id];
                        $withdrawOperation->save();
                    }
                }
            }

            foreach ($transferTransactions as &$transferTransaction) {
                $account->refresh();
                $balance = $account->getBalance();
                $balanceWithEGP = round($balance * $rates[$account->currency_id],2);
                if($balanceWithEGP <= 1500){
                    $transferTransaction->delete();
                    continue;
                }
                $amountWithEGP = rand(100,1000);
                $currency = $accountBankCurrencies->random();
                $amount = round($amountWithEGP / $currency->pivot->rate , 2);
                $transferOperation = new Transfer();
                $transferOperation->transaction_id = $transferTransaction->id;
                $transferOperation->currency_id = $currency->id;
                $transferOperation->amount = $amount;
                $transferOperation->rate = $currency->pivot->rate;
                $transferOperation->account_rate = $rates[$account->currency_id];
                $transferOperation->to_bank_name = $banks[rand(0 ,count($banks) -1)];
                $transferOperation->to_bank_account_number = $faker->bankAccountNumber;
                $transferOperation->save();
            }

        }


    }
}
