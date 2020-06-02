<?php

use Illuminate\Database\Seeder;
use App\Currency;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currencies = [
            'EGP',
            'USD',
            'SAR',
            'EURO'
        ];

        if(Currency::query()->whereIn('name',$currencies)->first()){
            return;
        }

        foreach ($currencies as $currency) {
            $currencyRecord = new Currency();
            $currencyRecord->name = $currency;
            $currencyRecord->save();
        }
    }
}
