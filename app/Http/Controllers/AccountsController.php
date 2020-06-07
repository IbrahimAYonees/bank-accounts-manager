<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as StandardRequest;
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
    public function index(StandardRequest $request)
    {
        return new AccountsCollection(
            Account::withRelations()
                ->sort($request['sortable'],$request['sortType'])
                ->paginate(10)
        );
    }

    /**
     * @param Account $account
     * @return AccountResource|\Illuminate\Http\JsonResponse
     */
    public function show(Account $account)
    {
        if(!$this->isUsersAccount($account)){
            return response()->json(401);
        }
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
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function update(Account $account,AccountRequest $request)
    {
        if(!$this->isUsersAccount($account)){
            return response()->json(401);
        }
        $account->bank_id = $request->bank_id;
        $account->currency_id = $request->currency_id;
        $account->branch = $request->branch;
        $account->account_type = $request->type;
        return $account->save();
    }

    /**
     * @param Account $account
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function deactivate(Account $account)
    {
        if(!$this->isUsersAccount($account)){
            return response()->json(401);
        }
        $account->active = false;
        return $account->save();
    }

    /**
     * @param Account $account
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function activate(Account $account)
    {
        if(!$this->isUsersAccount($account)){
            return response()->json(401);
        }
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

    /**
     * @param Account $account
     * @return bool
     */
    private function isUsersAccount(Account $account)
    {
        return auth()->user()->id == $account->user_id;
    }
}
