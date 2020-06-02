<?php

use Illuminate\Database\Seeder;
use App\Bank;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $banks = [
            'CIB',
            'QNB',
            'NBD',
            'HSBC'
        ];

        if(Bank::query()->whereIn('name',$banks)->first()){
            return;
        }

        foreach ($banks as $bank) {
            $bankRecord = new Bank();
            $bankRecord->name = $bank;
            $bankRecord->save();
        }
    }
}
