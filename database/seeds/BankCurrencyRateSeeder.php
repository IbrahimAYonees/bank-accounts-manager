<?php

use Illuminate\Database\Seeder;
use App\Bank;
use App\Currency;

class BankCurrencyRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $banks = Bank::all();
        $currencies = Currency::all();
        $rates = [
            'EGP' => [100,100],
            'USD' => [1620,1630],
            'EURO' => [1830,1840],
            'SAR' => [430,440]
        ];

        foreach ($banks as $bank) {
            foreach ($currencies as $currency) {
                $bank->currencies()->attach($currency->id,[
                    'rate' => rand($rates[$currency->name][0],$rates[$currency->name][1]) / 100
                ]);
            }
            $bank->save();
        }
    }
}
