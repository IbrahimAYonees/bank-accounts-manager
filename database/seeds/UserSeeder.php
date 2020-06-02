<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(User::query()->where('email','=','ibrahim21383@gmail.com')->first()){
            return;
        }

        $user = new User();
        $user->name = 'Ibrahim';
        $user->email = 'ibrahim21383@gmail.com';
        $user->password = Hash::make('123456');
        $user->save();
    }
}
