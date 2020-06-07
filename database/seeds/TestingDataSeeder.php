<?php

use Illuminate\Database\Seeder;

class TestingDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(BankCurrencyRateSeeder::class);
        $this->call(AccountSeeder::class);
        $this->call(TransactionSeeder::class);
        $this->call(AccountOperationsSeeder::class);
    }
}
