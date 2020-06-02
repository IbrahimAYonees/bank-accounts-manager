<?php

namespace App\Http\Controllers;

use App\Account;
use App\Http\Resources\AccountsCollection;
use App\Http\Resources\AccountResource;
use App\Http\Requests\AccountRequest;
use Faker\Factory;

class AccountsController extends Controller
{
    /**
     * AccountsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @return AccountsCollection
     */
    public function index()
    {
        return new AccountsCollection(
            Account::withRelations()->paginate(10)
        );
    }

    /**
     * @param Account $account
     * @return AccountResource
     */
    public function show(Account $account)
    {
        return new AccountResource($account);
    }

    /**
     * @param AccountRequest $request
     * @return bool
     */
    public function store(AccountRequest $request)
    {
        $account = new Account();
        $account->bank_id = $request->bank_id;
        $account->currency_id = $request->currency_id;
        $account->user_id = auth()->user()->id;
        $account->branch = $request->branch;
        $account->account_type = $request->type;
        $account->account_number = $this->generateAccountNumber();
        return $account->save();
    }

    /**
     * @param Account $account
     * @param AccountRequest $request
     * @return bool
     */
    public function update(Account $account,AccountRequest $request)
    {
        $account->bank_id = $request->bank_id;
        $account->currency_id = $request->currency_id;
        $account->branch = $request->branch;
        $account->account_type = $request->type;
        return $account->save();
    }

    /**
     * @param Account $account
     * @return bool
     */
    public function deactivate(Account $account)
    {
        $account->active = false;
        return $account->save();
    }

    /**
     * @param Account $account
     * @return bool
     */
    public function activate(Account $account)
    {
        $account->active = true;
        return $account->save();
    }

    /**
     * @return string
     */
    private function generateAccountNumber()
    {
        $faker = Factory::create();
        return $faker->bankAccountNumber;
    }
}
