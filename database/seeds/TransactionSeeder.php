<?php

use Illuminate\Database\Seeder;
use App\Transaction;
use App\Account;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $accounts = Account::all();

        foreach ($accounts as $account) {
            for($i=0; $i<10; $i++){
                $transaction = new Transaction();
                $transaction->account_id = $account->id;
                $transaction->save();
            }
        }
    }
}
